<?php
// admin.php - V14 (English UI + Forced Miniature/Main Image)

// 1. SECURITY
$admin_pass = getenv('PORTEFOLIO_ADMIN_PASS');
if (!$admin_pass) die("CRITICAL ERROR: Environment variable PORTEFOLIO_ADMIN_PASS is missing.");

if (!isset($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_PW'] !== $admin_pass) {
    header('WWW-Authenticate: Basic realm="Portfolio Admin"');
    header('HTTP/1.0 401 Unauthorized');
    die("Access Denied.");
}

// 2. LOAD CLASSES
require_once 'assets/php/Database/Database.php';
require_once 'assets/php/Database/Project.php';
require_once 'assets/php/Database/Text.php';
require_once 'assets/php/Database/Link.php';
require_once 'assets/php/Database/Techno.php';

try {
    $projectDB = new Project();
    $textDB    = new Text();
    $linkDB    = new Link();
    $technoDB  = new Techno();
} catch (Exception $e) { die("Database Error: ".$e->getMessage()); }

// 3. LOAD LANGUAGES
$availableLangs = ['fr', 'en'];
if (is_dir('assets/locales/')) {
    $scanned = [];
    foreach (glob('assets/locales/*.php') as $f) {
        $c = basename($f, '.php');
        if ($c !== 'trad') $scanned[] = $c;
    }
    if($scanned) $availableLangs = $scanned;
}

$msg = ""; $error = "";

// 4. FORM PROCESSING
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        
        // --- ADD TECHNO ---
        if (isset($_POST['add_techno']) && !empty($_POST['tech_name'])) {
            if ($technoDB->add($_POST['tech_name'])) $msg = "Technology added successfully.";
            else $error = "Failed to add technology.";
        }

        // --- ADD PROJECT ---
        elseif (isset($_POST['add_project'])) {
            $slug = trim($_POST['project_slug']);
            if (empty($slug)) throw new Exception("Project Slug is required.");

            // VALIDATION: Check for 'miniature' and at least 2 images
            $labels = $_POST['img_labels'] ?? [];
            if (!in_array('miniature', $labels)) throw new Exception("Error: An image labeled 'miniature' is mandatory.");
            
            // Count valid files uploaded
            $validFiles = 0;
            foreach ($_FILES['imgs']['name'] as $name) { if(!empty($name)) $validFiles++; }
            if ($validFiles < 2) throw new Exception("Error: You must upload at least 2 images (Miniature + Main).");


            // 1. Create Project
            $pid = $projectDB->create($slug);
            if (!$pid) throw new Exception("Could not retrieve Project ID.");

            // 2. Insert Texts
            foreach (['nom', 'resume', 'details'] as $field) {
                foreach ($availableLangs as $lang) {
                    $val = $_POST[$field . '_' . $lang] ?? '';
                    if (trim($val) !== '') $textDB->create($pid, $field, $lang, $val);
                }
            }

            // 3. Insert Images (Link Class)
            if (isset($_FILES['imgs'])) {
                $dir = 'assets/images/projet/' . strtolower($slug) . '/';
                if (!is_dir($dir)) mkdir($dir, 0777, true);

                foreach ($_FILES['imgs']['tmp_name'] as $i => $tmp) {
                    if (empty($tmp) || $_FILES['imgs']['error'][$i] !== 0) continue;

                    $orig = $_FILES['imgs']['name'][$i];
                    $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
                    if (!in_array($ext, ['png', 'webp', 'jpg', 'jpeg'])) continue;

                    $lbl = $_POST['img_labels'][$i] ?: 'img'.$i; // Should not happen given validation
                    $fname = $lbl . '.' . $ext;

                    if (move_uploaded_file($tmp, $dir . $fname)) {
                        $path = "assets/images/projet/$slug/$fname";
                        
                        // Store path in Link table
                        $linkDB->create($pid, $lbl, $path);

                        // Store captions in Text table
                        foreach ($availableLangs as $lang) {
                            $cap = $_POST["img_caption_{$i}_{$lang}"] ?? '';
                            if ($cap) $textDB->create($pid, "img_{$lbl}_caption", $lang, $cap);
                        }
                    }
                }
            }

            // 4. Insert Links
            if (!empty($_POST['links'])) {
                foreach ($_POST['links'] as $l) {
                    if (empty($l['url']) || empty($l['label'])) continue;
                    $linkDB->create($pid, $l['label'], $l['url']);
                    foreach ($availableLangs as $lang) {
                        $txt = $l['text'][$lang] ?? '';
                        if ($txt) $textDB->create($pid, "link_" . $l['label'], $lang, $txt);
                    }
                }
            }

            // 5. Link Technos
            if (!empty($_POST['technos'])) {
                foreach ($_POST['technos'] as $tName) {
                    $technoDB->create($pid, $tName);
                }
            }

            $msg = "Project '$slug' published successfully!";
        }

    } catch (Exception $e) { $error = $e->getMessage(); }
}

// Fetch Technos for UI
$technosList = [];
try { $technosList = $technoDB->fetchByProject(0); } catch(Exception $e){}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Admin</title>
    <style>
        :root { --primary: #007bff; --bg: #f4f7f6; --text: #333; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); padding: 20px; max-width: 950px; margin: 0 auto; }
        
        .header { display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #ddd; padding-bottom:10px; margin-bottom:20px; }
        a { color: #666; text-decoration: none; } a:hover { color: var(--primary); }
        
        .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 25px; }
        h2 { margin-top: 0; color: #2c3e50; font-size: 1.25rem; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; margin-bottom: 20px; }
        h3 { font-size: 0.9rem; text-transform: uppercase; color: #888; margin-top: 25px; margin-bottom: 10px; letter-spacing: 0.5px; }

        .msg { padding: 15px; border-radius: 4px; margin-bottom: 20px; font-weight: 500; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c2c7; }

        label { display: block; margin-top: 10px; font-weight: 600; font-size: 0.9rem; }
        input[type="text"], input[type="url"], input[type="file"], textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        
        .row-group { background: #f9fafb; padding: 15px; border: 1px solid #e1e4e8; border-radius: 6px; margin-bottom: 10px; display: flex; gap: 15px; }
        .col { flex: 1; } .col-2 { flex: 2; }
        
        .locked-input { background-color: #e9ecef; cursor: not-allowed; color: #495057; font-weight: bold; }
        
        .techno-container { display: flex; flex-wrap: wrap; gap: 8px; }
        .techno-pill { background: #fff; border: 1px solid #ccc; padding: 5px 12px; border-radius: 20px; cursor: pointer; user-select: none; font-size: 0.9rem; }
        .techno-pill:hover { background: #e2e6ea; }

        button.main-btn { width: 100%; padding: 15px; background: var(--primary); color: white; border: none; border-radius: 5px; font-size: 1.1rem; cursor: pointer; font-weight: bold; margin-top: 20px; }
        button.add-btn { background: #6c757d; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; font-size: 0.8rem; margin-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Portfolio Administration</h1>
        <a href="/">← Back to Website</a>
    </div>

    <?php if($msg): ?><div class="msg success"><?= $msg ?></div><?php endif; ?>
    <?php if($error): ?><div class="msg error"><?= $error ?></div><?php endif; ?>

    <div class="card" style="background:#f0f4f8;">
        <h3>Add New Technology</h3>
        <form method="post" style="display:flex; gap:10px; align-items:flex-end;">
            <input type="hidden" name="add_techno" value="1">
            <div style="flex-grow:1">
                <input type="text" name="tech_name" placeholder="Name (e.g., Python)" required>
            </div>
            <button type="submit" style="background:#28a745; color:white; border:none; padding:10px 20px; border-radius:4px; cursor:pointer;">Add</button>
        </form>
    </div>

    <div class="card">
        <h2>New Project</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="add_project" value="1">
            
            <label>Project Slug (Unique ID)</label>
            <input type="text" name="project_slug" required placeholder="e.g.: driving-logbook" pattern="[a-z0-9-]+" title="Lowercase and hyphens only">

            <h3>Content</h3>
            <?php foreach(['nom'=>'Title', 'resume'=>'Summary', 'details'=>'Details'] as $key => $label): ?>
                <div style="margin-bottom:15px; border-left:3px solid #007bff; padding-left:10px;">
                    <label style="margin-top:0; color:#007bff;"><?= $label ?></label>
                    <?php foreach($availableLangs as $lang): ?>
                        <input type="text" name="<?= $key ?>_<?= $lang ?>" placeholder="In <?= strtoupper($lang) ?>..." style="margin-top:5px;">
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

            <h3>Images (Minimum 2)</h3>
            <div id="img-container"></div>
            <button type="button" class="add-btn" onclick="addImg('', false)">+ Add Another Image</button>

            <h3>Links</h3>
            <div id="link-container"></div>
            <button type="button" class="add-btn" onclick="addLink()">+ Add Link</button>

            <h3>Technologies</h3>
            <div class="techno-container">
                <?php if(empty($technosList)): ?>
                    <p style="color:#888; font-style:italic;">No technologies found. Add one above.</p>
                <?php else: ?>
                    <?php foreach($technosList as $tName): ?>
                        <label class="techno-pill">
                            <input type="checkbox" name="technos[]" value="<?= htmlspecialchars($tName) ?>">
                            <?= htmlspecialchars($tName) ?>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <button type="submit" class="main-btn">PUBLISH PROJECT</button>
        </form>
    </div>

    <script>
        const langs = <?= json_encode($availableLangs) ?>;
        let imgCount = 0;
        let linkCount = 0;

        // Function to add image row. 
        // fixedLabel: string (if set, input is readonly)
        // required: bool
        function addImg(fixedLabel = '', isRequired = false) {
            let labelAttr = fixedLabel ? `value="${fixedLabel}" readonly class="locked-input"` : `placeholder="Label (e.g. cover)"`;
            let reqAttr = isRequired ? 'required' : '';
            
            let html = `
            <div class="row-group">
                <div class="col">
                    <label>Label</label>
                    <input type="text" name="img_labels[${imgCount}]" ${labelAttr}>
                </div>
                <div class="col-2">
                    <label>File (WebP/PNG)</label>
                    <input type="file" name="imgs[${imgCount}]" accept=".webp,.png,.jpg,.jpeg" ${reqAttr}>
                </div>
                <div class="col-2">
                    <label>Captions (Alt Text)</label>
                    ${langs.map(l => `<input type="text" name="img_caption_${imgCount}_${l}" placeholder="${l.toUpperCase()}" style="margin-bottom:2px">`).join('')}
                </div>
            </div>`;
            document.getElementById('img-container').insertAdjacentHTML('beforeend', html);
            imgCount++;
        }

        function addLink() {
            let html = `
            <div class="row-group">
                <div class="col">
                    <label>Label (Slug)</label>
                    <input type="text" name="links[${linkCount}][label]" placeholder="e.g. github">
                </div>
                <div class="col-2">
                    <label>URL</label>
                    <input type="url" name="links[${linkCount}][url]" placeholder="https://...">
                </div>
                <div class="col-2">
                    <label>Button Text</label>
                    ${langs.map(l => `<input type="text" name="links[${linkCount}][text][${l}]" placeholder="${l.toUpperCase()}" style="margin-bottom:2px">`).join('')}
                </div>
            </div>`;
            document.getElementById('link-container').insertAdjacentHTML('beforeend', html);
            linkCount++;
        }

        // INIT: Force 2 images rows
        // 1. Miniature (Locked)
        addImg('miniature', true);
        // 2. Main Image (Free label but required)
        addImg('', true);

        // Optional: Add default link row
        addLink();
    </script>
</body>
</html>