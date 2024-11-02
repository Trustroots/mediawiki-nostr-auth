function createAuthEvent() {
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

async function authWithNostr() {
    // https://github.com/nostr-protocol/nips/issues/154
    // https://nostrlogin.org/
    const pubkey = await window.nostr.getPublicKey();
    console.log("pubKey: " + pubkey);

    const authEvent = createAuthEvent();
    const signEvent = await window.nostr.signEvent(authEvent);
    const signature = signEvent.sig
    console.log("signature: " + signature);

    if (!isValidSignature(signEvent)) {
        throw new AuthenticationError();
    } else {
        console.log("Signature Valid.");
        const data = {
            npub: pubkey,
            signature: "valid"
        };

        /*  
            create_account.js
        
            MediaWiki API Demos
            Demo of `createaccount` module: Create an account on a wiki without the
            special authentication extensions

            MIT license
        */


        var wikiUrl = "http://localhost",
            endPoint = wikiUrl + "/mediawiki/api.php";

        // Step 1: GET request to fetch createaccount token
        function getCreateAccountToken() {
            const params_0 = new URLSearchParams({
                action: "query",
                meta: "tokens",
                type: "createaccount",
                format: "json"
            });

            fetch(`${endPoint}?${params_0}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    createaccount(data.query.tokens.createaccounttoken);
                })
                .catch(error => {
                    console.error("Fetch error:", error);
                });
        }

        // Step 2: POST request with the fetched token and other data (user information,
        // return URL, etc.)  to the API to create an account
        function createaccount(createaccount_token) {
            const params_1 = {
                action: "createaccount",
                username: (pubkey + String(Math.random())),
                password: "your_password",
                retype: "your_password",
                createreturnurl: wikiUrl,
                createtoken: createaccount_token,
                format: "json"
            };

            fetch(endPoint, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams(params_1)
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    console.log(data.createaccount.status);
                })
                .catch(error => {
                    console.error('Error:', error);
                });

        }

        // Start From Step 1
        getCreateAccountToken();
    }
}