function createAuthEvent() {
    // dummy event
    const authEvent = {
        kind: 12345, // event kind 
        created_at: Math.floor(Date.now() / 1000), // event timestamp
        tags: [],    // event tags
        content: ""  // event content
    }
    return authEvent;
}


function isValidSignature(event) {
    const isValid = window.NostrTools.verifyEvent(event);
    return isValid;
}


// from https://www.mediawiki.org/wiki/API:Account_creation/Sample_code_1
var WIKIURL = "http://localhost";
var ENDPOINT = WIKIURL + "/mediawiki/api.php";

function getCreateAccountToken(username) {
    const params_0 = new URLSearchParams({
        action: "query",
        meta: "tokens",
        type: "createaccount",
        format: "json"
    });

    fetch(`${ENDPOINT}?${params_0}`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            createAccount(username, data.query.tokens.createaccounttoken);
        })
        .catch(error => {
            console.error("Fetch error:", error);
        });
}


function createAccount(username, createaccount_token) {
    // check if mediawiki account exists
    const params = new URLSearchParams({
        action: "query",
        list: "users",
        ususers: username,
        usprop: "blockinfo|groups|editcount|registration|emailable|gender",
        format: "json"
    });

    fetch(`${ENDPOINT}?${params}`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            if (data.query.users[0].missing === "") {
                // create account
                const params_1 = new URLSearchParams({
                    action: "createaccount",
                    username: username,
                    password: "your_password",
                    retype: "your_password",
                    createreturnurl: WIKIURL,
                    createtoken: createaccount_token,
                    format: "json"
                });

                fetch(ENDPOINT, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: params_1
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        console.log(data.createaccount.status);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                throw new Error("User already exists");
            }
        })
}


async function authWithNostr() {
    const username = document.getElementById('username').value;
    const domain = document.getElementById("domain").value;
    console.log("Username: " + username);
    // check for trustroots user
    let profile = await window.NostrTools.nip05.queryProfile(fullname = username + domain)
    console.log(profile)

    const pubkey_nip05 = profile.pubkey

    // use browser extension to verify the user
    // from https://github.com/nostr-protocol/nips/issues/154
    // from https://nostrlogin.org/
    if (!window.nostr) {
        throw new AuthenticationError("No Nostr Browser Extension found");
    }
    const pubkey_nip07 = await window.nostr.getPublicKey();
    if (pubkey_nip05 != pubkey_nip07) {
        throw new AuthenticationError("Nip05-Nip07 Pubkey mismatch");
    }
    const pubkey = pubkey_nip07;

    console.log("pubKey: " + pubkey);

    const authEvent = createAuthEvent();
    const signEvent = await window.nostr.signEvent(authEvent);
    const signature = signEvent.sig
    console.log("signature: " + signature);

    if (!isValidSignature(signEvent)) {
        throw new AuthenticationError("Invalid Signature - Seems like that an incorrect private key was used to sign the event");
    }
    console.log("Signature Valid.");

    getCreateAccountToken(username);
}