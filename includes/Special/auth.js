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
        
        // Send POST request
        fetch("/mediawiki/extensions/mediawiki-nostr-auth/includes/Special/createUser.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json" // Sending data as JSON
            },
            body: JSON.stringify(data) // Convert the data object to a JSON string
        })
        .then(response => response.json())
        .then(result => {
            console.log("Success:", result); // Handle response from the server
        })
        .catch(error => {
            console.error("Error:", error); // Handle any errors
        });
    }
}