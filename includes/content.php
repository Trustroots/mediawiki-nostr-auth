<p>We use the signature of a Nostr event as the <b>Nostr password</b>. With this signature we can verify that you have access to the private key.</p>

<p>You need to a have a NIP-07 Nostr signing extension installed in your browser e.g. <a href="https://getalby.com/">Alby</a> or nos2x</p>

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

   
<script>
    // Function to handle the Enter key press, probably better to refactor this into a <form>
    document.getElementById('username').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            logInWithNostr();
        }
    });
</script>