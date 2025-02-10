function createAuthEvent() {
    // dummy event
    const authEvent = {
        kind: 12345, // event kind 
        created_at: 0, // event timestamp
        tags: [],    // event tags
        content: ""  // event content
    }
    return authEvent;
}


function isValidSignature(event) {
    const isValid = window.NostrTools.verifyEvent(event);
    return isValid;
}


async function logInWithNostr() {
    console.log(mw.config.get('wgPluggableAuth_EnableLocalLogin'));
    const username = document.getElementById('username').value;
    const domain = document.getElementById("domain").value;
    console.log("Username: " + username);

    // use browser extension to verify the user
    // from https://github.com/nostr-protocol/nips/issues/154
    // from https://nostrlogin.org/
    if (!window.nostr) {
        throw new AuthenticationError("No Nostr Browser Extension found");
    }

    console.log("test")

    // check for trustroots user
    // let profile = await window.NostrTools.nip05.queryProfile(fullname = username + domain)
    // console.log(profile)

    // const pubkey_nip05 = profile.pubkey

    const pubkey_nip07 = await window.nostr.getPublicKey();
    // if (pubkey_nip05 != pubkey_nip07) {
    //     throw new AuthenticationError("Nip05-Nip07 Pubkey mismatch");
    // }

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
    document.getElementById("nostr_password").innerHTML = "<b>Nostr password</b>: " + signEvent.sig;
    document.getElementById("forward").innerHTML = "Copy the password and go to <a href=\""
        + mw.config.get('wgServer')
        + mw.config.get('wgScriptPath')
        + "/index.php?title=Special:UserLogin&nostr_nip05=trustroots.org&"
        + "nostr_password=" + signEvent.sig
	+ "&wpName=" + username
	+ "\">the Log In page of this Wiki</a> to continue.";
}
