<p>You can create a new account for this wiki if you already have an account on any of the websites we trust (e.g. trustroots.org). For this to work, you have to link your Nostr profile to this website or conveniently have your Nostr profile created there.</p>

<p>On trustroots.org, you can create a Nostr profile via https://www.trustroots.org/profile/edit/networks.</p>

<p>To use Nostr in your browser you need a Nostr browser extension like <a href="https://getalby.com/">Alby</a>  that will handle all things Nostr for you and link your Nostr profile to it. Imagine this as a password manager, but for Nostr.</p>

<p>Next you need to provide your username on that website and the domain of that website.</p>

<p>After clicking 'Get Nostr password' your Nostr browser extension will request you to sign a Nostr event - proceed.</p>

<input type="text" id="username" placeholder="Enter username">
<select id="domain">
    <?php foreach ($NostrLoginDomains as $domain): ?>
    <option value="@<?php echo $domain; ?>">@<?php echo $domain; ?></option>
  <?php endforeach; ?>
</select>
<button onclick="logInWithNostr()">Get Nostr password</button>
<script src="https://unpkg.com/nostr-tools/lib/nostr.bundle.js"></script>
<script src="<?php echo $this->config->wgServer ?><?php echo $this->config->wgScriptPath ?>/extensions/mediawiki-nostr-auth/includes/auth.js?<?php echo date('Ymd-His') ?>"></script>
<p id="nostr_password"></p>
<p id="forward"></p>

<p> -- More detailed information -- </p>

<p>We use the signature of a Nostr event as the <b>One-time Nostr password</b>. With this signature we can verify that you have access to the private key.</p>

<p>You need to a have a <a href="https://github.com/nostr-protocol/nips/blob/master/07.md">NIP-07</a> Nostr signing extension installed in your browser e.g. <a href="https://getalby.com/">Alby</a> or nos2x</p>

   
<script>
    // Function to handle the Enter key press, probably better to refactor this into a <form>
    document.getElementById('username').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            logInWithNostr();
        }
    });
</script>
