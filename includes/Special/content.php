<input type="text" id="username" placeholder="Enter username">
<select id="domain">
    <?php foreach ($NostrLoginDomains as $domain): ?>
    <option value="@<?php echo $domain; ?>">@<?php echo $domain; ?></option>
  <?php endforeach; ?>
</select>
<button onclick="authWithNostr()">Sign Up with Nostr</button>
<button onclick="logInWithNostr()">Log In with Nostr</button>
<script src="https://unpkg.com/nostr-tools/lib/nostr.bundle.js"></script>
<script src="/mediawiki/extensions/mediawiki-nostr-auth/includes/Special/auth.js"></script>