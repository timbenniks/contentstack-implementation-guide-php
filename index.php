<?php
const API_KEY = 'blte766efb491f96715';
const DELIVERY_TOKEN = 'cs620decb0e6bb175e31210ce9';
const ENVIRONMENT = 'preview';
const PREVIEW_TOKEN = 'csa128deacffe0b26386090915';
const REGION = 'EU';
const LANG = 'en-us';
const HOSTURL = REGION == 'EU' ? 'eu-app.contentstack.com' : 'app.contentstack.com';

$baseurl = REGION == 'EU' ? "eu-cdn.contentstack.com" : "cdn.contentstack.com";
$content_type_uid = $_GET['content_type_uid'] ?? 'page';
$entry_uid = $_GET['entry_uid'] ?? 'blte55cf3411ecaee0e';
$live_preview = $_GET['live_preview'] ?? '';

if ($live_preview) {
  $baseurl = REGION == 'EU' ? "eu-rest-preview.contentstack.com" : "rest-preview.contentstack.com";
}

function makeApiRequest($url, $live_preview) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  if($live_preview) {
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json',
      'api_key: ' . API_KEY,
      'preview_token: ' . PREVIEW_TOKEN,
      'live_preview: ' . $live_preview,
      'access_token: ' . DELIVERY_TOKEN
    ]);
  }
  else {
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json',
      'api_key: ' . API_KEY,
      'access_token: ' . DELIVERY_TOKEN
    ]);
  }
      
  $response = curl_exec($ch);
  curl_close($ch);
  return json_decode($response, true) ?? [];
}

function createEditableTags($content_type_uid, $entry_uid, $lang, $field) {
  return sprintf('data-cslp=%s.%s.%s.%s', $content_type_uid, $entry_uid, $lang, $field);
}

$url = sprintf('https://%s/v3/content_types/%s/entries/%s?environment=%s', $baseurl, urlencode($content_type_uid), urlencode($entry_uid), ENVIRONMENT);
$result = makeApiRequest($url, $live_preview ? $live_preview : null);
$page = $result['entry']
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contentstack Implementation guide PHP</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <script type="module">
      import ContentstackLivePreview from 'https://esm.sh/@contentstack/live-preview-utils@3.0.1';
      ContentstackLivePreview.init({
        ssr: true,
        enable: true,
        mode: "preview",
        stackDetails: {
          apiKey: "<?=API_KEY?>",
          environtment: "<?=ENVIRONMENT?>"
        },
        clientUrlParams: {
          host: "<?=HOSTURL?>"
        },
        editButton: {
          enable: true,
        }
      });
    </script>
</head>
<body>
  <main class="max-w-screen-2xl mx-auto">
    <section class="p-4">
      <ul class="mb-8 text-sm">
        <li>
          live_preview_hash: <code><?= htmlspecialchars($live_preview) ?></code>
        </li>    
        <li>
          content_type_uid: <code><?= htmlspecialchars($content_type_uid) ?></code>
        </li>
        <li>
          entry_uid: <code><?= htmlspecialchars($entry_uid) ?></code>
        </li>
      </ul>
      
      <?php if (isset($page['title']) && $page['title']): ?>
        <h1 class="text-4xl font-bold mb-4"
          <?= createEditableTags($content_type_uid, $entry_uid, LANG, "title") ?>>
          <?= htmlspecialchars($page['title']) ?>
        </h1>
      <?php endif; ?>

      <?php if (isset($page['description']) && $page['description']): ?>
        <p class="mb-4"
          <?= createEditableTags($content_type_uid, $entry_uid, LANG, "description") ?>>
          <?= htmlspecialchars($page['description']) ?>
        </p>
      <?php endif; ?>

      <?php if (isset($page['image']) && $page['image']): ?>
        <img class="mb-4"
          <?= createEditableTags($content_type_uid, $entry_uid, LANG, "image.url") ?>
          width="300"
          height="300"
          src="<?= htmlspecialchars($page['image']['url']) ?>"
          alt="<?= htmlspecialchars($page['image']['title']) ?>"
          
        />
      <?php endif; ?>

      <?php if (isset($page['rich_text']) && $page['rich_text']): ?>
        <div <?= createEditableTags($content_type_uid, $entry_uid, LANG, "rich_text") ?>>
          <?= $page['rich_text'] ?>
        </div>
      <?php endif; ?>
    </section>

  </main>
</body>
</html>