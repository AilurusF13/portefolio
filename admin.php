<?php
// admin.php - V23 (Fix Translation Warnings)

// 1. SECURITY & ENV
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) continue; 
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value, " \t\n\r\0\x0B\"'"); 
        putenv("$name=$value");
        $_ENV[$name] = $value;
    }
}

require_once "assets/locales/trad.php"; 

$admin_pass = getenv('PORTEFOLIO_ADMIN_PASS');
if (!$admin_pass) die("Config Error");

// On récupère le mot de passe envoyé par le navigateur (s'il y en a un)
$input_pass = $_SERVER['PHP_AUTH_PW'] ?? null;
$input_user = $_SERVER['PHP_AUTH_USER'] ?? null;

if ($input_pass !== $admin_pass && $input_user !== 'admin') {
    // Astuce : Si le mdp est faux, on change le nom du realm (ex: "Admin (Retry)")
    // Le navigateur va alors "oublier" l'ancien mdp et redemander la popup.
    $realm = isset($input_pass) ? "Portfolio Admin (Retry)" : "Portfolio Admin";
    
    header('WWW-Authenticate: Basic realm="' . $realm . '"');
    header('HTTP/1.0 401 Unauthorized');
    
    // Message affiché si l'utilisateur clique sur "Annuler"
    die("Acces denied. Please try again.");
}

// 2. LOAD CLASSES
// ... le reste du code (Database, Project, etc.) ...

// 2. LOAD CLASSES
require_once 'assets/php/Database/Database.php';
require_once 'assets/php/Database/Project.php';
require_once 'assets/php/Database/Text.php';
require_once 'assets/php/Database/Link.php';
require_once 'assets/php/Database/Techno.php';

class DBReader extends Database { 
    public function q($s){ return $this->db->query($s); } 
    public function exec($s){ return $this->db->exec($s); }
}

try {
    $reader    = new DBReader();
    $projectDB = new Project();
    $textDB    = new Text();
    $linkDB    = new Link();
    $technoDB  = new Techno();
} catch (Exception $e) { die("DB Error: ".$e->getMessage()); }

// 3. CONFIG & LANGS
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
$jsonPath = 'assets/data/featured.json';

// Ensure assets/data exists
if (!is_dir('assets/data')) mkdir('assets/data', 0777, true);

// Helper: Load Featured
function getFeatured($path) {
    if (!file_exists($path)) return [];
    return json_decode(file_get_contents($path), true) ?? [];
}

// Helper: Save Featured
function saveFeatured($path, $data) {
    file_put_contents($path, json_encode(array_values($data), JSON_PRETTY_PRINT));
}

function deleteFolder($dir) {
    if (!is_dir($dir)) return;
    $files = array_diff(scandir($dir), ['.','..']);
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? deleteFolder("$dir/$file") : unlink("$dir/$file");
    }
    rmdir($dir);
}

// 4. DATA LOADING
$editMode = false;
$currentTexts = []; $currentLinks = []; $currentTechnos = []; $currentImages = []; $allProjects = []; 

// Load Projects List
try {
    $stmt = $reader->q("SELECT id, label FROM project ORDER BY id DESC");
    if($stmt) $allProjects = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
} catch(Exception $e) {}

// Load Featured List
$featuredSlugs = getFeatured($jsonPath);

// Edit Mode Logic
if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
    $pid = (int)$_GET['edit_id'];
    if (array_key_exists($pid, $allProjects)) {
        $editMode = true;
        $slug = $allProjects[$pid];
        
        $stmt = $reader->q("SELECT label, lang, txt FROM ptext WHERE pid = $pid");
        $rawTexts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rawTexts as $row) {
            $tag = $row['label']; $lang = $row['lang']; $txt = $row['txt'];
            if (strpos($tag, 'img_') === 0 && strpos($tag, '_path') !== false) {
                $lbl = str_replace(['img_', '_path'], '', $tag);
                $currentImages[$lbl] = $txt;
            } else { $currentTexts[$tag][$lang] = $txt; }
        }
        try {
            $rawLinks = $linkDB->fetchAllLinks($pid); 
            foreach($rawLinks as $l) {
                if (strpos($l['url'], 'assets/images/') !== false) {
                    $currentImages[$l['label']] = $l['url'];
                } else {
                    $currentLinks[] = ['label' => $l['label'], 'url' => $l['url']];
                }
            }
        } catch(Exception $e){}
        try {
            $rawTechs = $technoDB->fetchByProject($pid);
            foreach($rawTechs as $tName) $currentTechnos[$tName] = true;
        } catch(Exception $e){}
    }
}

// 5. POST PROCESSING
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // --- SLIDER: ADD ---
        if (isset($_POST['add_featured'])) {
            $slugToAdd = $_POST['featured_slug'];
            if (!in_array($slugToAdd, $featuredSlugs)) {
                $featuredSlugs[] = $slugToAdd;
                saveFeatured($jsonPath, $featuredSlugs);
                $msg = "Project added to slider.";
            }
        }
        // --- SLIDER: REMOVE ---
        elseif (isset($_POST['remove_featured'])) {
            $slugToRemove = $_POST['featured_slug'];
            if (($key = array_search($slugToRemove, $featuredSlugs)) !== false) {
                unset($featuredSlugs[$key]);
                saveFeatured($jsonPath, $featuredSlugs);
                $msg = "Project removed from slider.";
            }
        }

        // --- PROJECT: DELETE ---
        elseif (isset($_POST['delete_project']) && $editMode) {
            $pid = (int)$_POST['project_id'];
            $slugToDelete = $_POST['project_slug_hidden'];
            
            // Remove from Slider JSON if present
            if (($key = array_search($slugToDelete, $featuredSlugs)) !== false) {
                unset($featuredSlugs[$key]);
                saveFeatured($jsonPath, $featuredSlugs);
            }

            deleteFolder('assets/images/projet/' . strtolower($slugToDelete));
            $reader->exec("DELETE FROM ptext WHERE pid = $pid");
            $reader->exec("DELETE FROM plink WHERE pid = $pid");
            $reader->exec("DELETE FROM ptechno WHERE pid = $pid");
            $reader->exec("DELETE FROM project WHERE id = $pid");
            header("Location: admin.php?msg=deleted"); exit;
        }

        // --- TECHNO LOGIC ---
        elseif (isset($_POST['add_techno']) && !empty($_POST['tech_name'])) {
            if ($technoDB->add($_POST['tech_name'])) $msg = "Technology added.";
            else $error = "Failed to add technology.";
        }
        elseif (isset($_POST['update_techno'])) {
            $tid = (int)$_POST['tech_id']; $newName = trim($_POST['tech_new_name']);
            if($newName) $reader->q("UPDATE techno SET name = '$newName' WHERE id = $tid");
        }
        elseif (isset($_POST['delete_techno'])) {
            $tid = (int)$_POST['tech_id'];
            $reader->exec("DELETE FROM ptechno WHERE tid = $tid");
            $reader->exec("DELETE FROM techno WHERE id = $tid");
        }

        // --- PROJECT: SAVE ---
        elseif (isset($_POST['save_project'])) {
            $slug = trim($_POST['project_slug']);
            if (empty($slug)) throw new Exception("Slug required.");
            $isUpdate = isset($_POST['project_id']) && !empty($_POST['project_id']);
            $pid = $isUpdate ? (int)$_POST['project_id'] : null;

            if (!$isUpdate) {
                $labels = $_POST['img_labels'] ?? [];
                if (!in_array('miniature', $labels)) throw new Exception("Error: 'miniature' is mandatory.");
                $validFiles = 0;
                foreach ($_FILES['imgs']['name'] as $name) { if(!empty($name)) $validFiles++; }
                if ($validFiles < 2) throw new Exception("Error: Minimum 2 images required.");
            }

            if ($isUpdate) $reader->exec("UPDATE project SET label = '$slug' WHERE id = $pid");
            else $pid = $projectDB->create($slug);
            if (!$pid) throw new Exception("Project creation failed.");

            $reader->exec("DELETE FROM ptext WHERE pid = $pid");
            foreach (['nom', 'resume', 'details'] as $field) {
                foreach ($availableLangs as $lang) {
                    $val = $_POST[$field . '_' . $lang] ?? '';
                    if (trim($val) !== '') $textDB->create($pid, $field, $lang, $val);
                }
            }

            $dir = 'assets/images/projet/' . strtolower($slug) . '/';
            if (!is_dir($dir)) mkdir($dir, 0777, true);
            $linkDB->delete($pid);

            $labels = $_POST['img_labels'] ?? [];
            $existingPaths = $_POST['img_paths_existing'] ?? []; 
            
            foreach ($labels as $i => $lbl) {
                if(empty($lbl)) continue;
                $finalPath = '';
                $hasFile = !empty($_FILES['imgs']['name'][$i]);
                if ($hasFile && $_FILES['imgs']['error'][$i] === 0) {
                    $ext = strtolower(pathinfo($_FILES['imgs']['name'][$i], PATHINFO_EXTENSION));
                    if (in_array($ext, ['png', 'webp', 'jpg', 'jpeg'])) {
                        if (isset($existingPaths[$i]) && file_exists($existingPaths[$i])) unlink($existingPaths[$i]);
                        $fname = $lbl . '.' . $ext;
                        if (move_uploaded_file($_FILES['imgs']['tmp_name'][$i], $dir . $fname)) $finalPath = "assets/images/projet/$slug/$fname";
                    }
                } else {
                    if (isset($existingPaths[$i])) $finalPath = $existingPaths[$i];
                }

                if ($finalPath) {
                    $linkDB->create($pid, $lbl, $finalPath);
                    foreach ($availableLangs as $lang) {
                        $cap = $_POST["img_caption_{$i}_{$lang}"] ?? '';
                        if ($cap) $textDB->create($pid, "img_{$lbl}_caption", $lang, $cap);
                    }
                }
            }

            if (!empty($_POST['links'])) {
                foreach ($_POST['links'] as $l) {
                    $url = trim($l['url'] ?? '');
                    $label = trim($l['label'] ?? '');

                    // 1. Si la ligne est totalement vide, on l'ignore (c'est normal)
                    if (empty($url) && empty($label)) continue;

                    // 2. Si l'un des deux manque, on bloque tout !
                    if (empty($label)) throw new Exception("Erreur : Le lien vers '$url' doit avoir un Label.");
                    if (empty($url)) throw new Exception("Erreur : Le label '$label' doit avoir une URL.");

                    // Si tout est bon, on insère
                    $linkDB->create($pid, $label, $url);
                    foreach ($availableLangs as $lang) {
                        $txt = $l['text'][$lang] ?? '';
                        if ($txt) $textDB->create($pid, "link_" . $label, $lang, $txt);
                    }
                }
            }

            $reader->exec("DELETE FROM ptechno WHERE pid = $pid");
            if (!empty($_POST['technos'])) { foreach ($_POST['technos'] as $tName) $technoDB->create($pid, $tName); }

            if($isUpdate) { header("Location: admin.php?edit_id=$pid&msg=updated"); exit; }
            else $msg = "Project Created.";
        }
    } catch (Exception $e) { $error = $e->getMessage(); }
}

$stmtT = $reader->q("SELECT id, name FROM techno ORDER BY name ASC");
$allTechnos = $stmtT->fetchAll(PDO::FETCH_ASSOC);
$technosList = array_column($allTechnos, 'name');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio - Admin</title>
    
    <link rel="stylesheet" href="assets/css/var.css">
    <link rel="stylesheet" href="assets/css/mobile.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/desktop.css">
    <link rel="stylesheet" href="assets/css/project-page.css">
    <link rel="stylesheet" href="assets/css/header.css">

    <style>
    /* === STYLE SPÉCIFIQUE ADMIN === */
    main {
        width: 100%; max-width: 1000px; margin: 0 auto;
        display: flex; flex-direction: column; gap: 2rem; padding: 20px;
    }

    .admin-section {
        background-color: var(--tertiary); color: var(--primary);
        padding: 2rem; border-radius: 8px;
        filter: drop-shadow(0px 2px 6px rgba(0,0,0,0.2)); margin-bottom: 2rem;
    }

    /* Correction Flow Titres + Boutons */
    .admin-section h2, .admin-section h3 {
        color: var(--primary); text-transform: uppercase;
        margin-top: 1.5rem; margin-bottom: 1rem; 
        display: block !important; width: 100%; 
        clear: both; /* Force le retour à la ligne après les flottants si besoin */
    }

    input, select, textarea {
        width: 100%; padding: 10px; margin-top: 5px; margin-bottom: 15px;
        border: 1px solid #aaa; border-radius: 4px;
        font-family: "Roboto Condensed", sans-serif;
        background: white; color: black; box-sizing: border-box;
    }
    input:focus { outline: 2px solid var(--primary); }

    label { font-weight: bold; display: block; margin-top: 10px; }

    .btn {
        padding: 10px 20px; background-color: var(--primary); color: var(--tertiary);
        border: 1px solid var(--primary); cursor: pointer;
        font-weight: bold; text-transform: uppercase; border-radius: 5px; transition: 0.3s;
        display: inline-block;
    }
    .btn:hover { background-color: var(--tertiary); color: var(--primary); }
    .btn-danger { background-color: #a71d2a; border-color: #a71d2a; color: white; }
    .btn-danger:hover { background-color: white; color: #a71d2a; }
    .btn-mini { padding: 5px 10px; font-size: 0.8rem; margin-top: 5px; display: block; width: fit-content; }

    .dynamic-row {
        background: rgba(0,0,0,0.05); padding: 15px; border-radius: 5px; margin-bottom: 10px;
        display: flex; gap: 15px; flex-wrap: wrap;
    }
    .col { flex: 1; min-width: 200px; }

    .techno-grid { display: flex; flex-wrap: wrap; gap: 10px; }
    .techno-item {
        display: flex; align-items: center; background: white; padding: 5px 10px;
        border: 1px solid #ccc; border-radius: 20px; cursor: pointer;
    }
    .techno-item input { margin: 0 5px 0 0; width: auto; }

    .alert { padding: 15px; margin-bottom: 20px; border-radius: 5px; font-weight: bold; text-align: center; }
    .alert-success { background: #d4edda; color: #155724; }
    .alert-error { background: #f8d7da; color: #721c24; }

    .slider-list-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px; border-bottom: 1px solid #ccc; font-size: 1.1rem;
    }
</style>
</head>
<body>
    
    <div class="background-img"></div>
    <?php if(file_exists('assets/php/header.php')) include 'assets/php/header.php'; ?>

    <main>
        <?php if($msg): ?><div class="alert alert-success"><?= $msg ?></div><?php endif; ?>
        <?php if($error): ?><div class="alert alert-error"><?= $error ?></div><?php endif; ?>
        <?php if(isset($_GET['msg']) && $_GET['msg']=='updated'): ?><div class="alert alert-success">Project Updated.</div><?php endif; ?>
        <?php if(isset($_GET['msg']) && $_GET['msg']=='deleted'): ?><div class="alert alert-success">Project Deleted.</div><?php endif; ?>


        <div class="admin-section" style="border-left: 5px solid #28a745;">
            <h2>FEATURED PROJECTS (SLIDER)</h2>
            
            <div style="background: white; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 20px;">
                <?php if(empty($featuredSlugs)): ?>
                    <div style="padding:15px; color:#888;">No projects in slider.</div>
                <?php else: ?>
                    <?php foreach($featuredSlugs as $fSlug): ?>
                        <div class="slider-list-item">
                            <span><?= htmlspecialchars($fSlug) ?></span>
                            <form method="post" style="margin:0;">
                                <input type="hidden" name="remove_featured" value="1">
                                <input type="hidden" name="featured_slug" value="<?= $fSlug ?>">
                                <button type="submit" class="btn btn-mini btn-danger">REMOVE</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <h3>ADD TO SLIDER</h3>
            <form method="post" style="display:flex; gap:10px; align-items:flex-end;">
                <input type="hidden" name="add_featured" value="1">
                <div style="flex-grow:1;">
                    <select name="featured_slug" style="margin-bottom:0;">
                        <?php foreach($allProjects as $pid_opt => $label_opt): ?>
                            <?php if(!in_array($label_opt, $featuredSlugs)): ?>
                                <option value="<?= htmlspecialchars($label_opt) ?>"><?= htmlspecialchars($label_opt) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">ADD</button>
            </form>
        </div>


        <div class="admin-section">
            <h2>TECHNOLOGIES</h2>
            <form method="post" style="display:flex; gap:10px; align-items:flex-end; margin-bottom: 20px;">
                <input type="hidden" name="add_techno" value="1">
                <div style="flex-grow:1"><input type="text" name="tech_name" placeholder="New Technology Name" required style="margin-bottom:0;"></div>
                <button type="submit" class="btn">ADD</button>
            </form>
            <div style="max-height: 200px; overflow-y: auto; padding:10px; border:1px solid #ddd; background:white;">
                <?php foreach($allTechnos as $t): ?>
                    <div style="display:flex; gap:10px; margin-bottom:5px; align-items:center;">
                        <form method="post" style="display:flex; width:100%; gap:5px;">
                            <input type="hidden" name="tech_id" value="<?= $t['id'] ?>">
                            <input type="text" name="tech_new_name" value="<?= htmlspecialchars($t['name']) ?>" style="margin:0; padding:5px;">
                            <button type="submit" name="update_techno" class="btn btn-mini" style="background:#e0a800;">RENAME</button>
                            <button type="submit" name="delete_techno" class="btn btn-mini btn-danger" onclick="return confirm('Delete completely?')">X</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="admin-section">
            <h2>SELECT PROJECT</h2>
            <form method="get">
                <select name="edit_id" onchange="this.form.submit()" style="font-size:1.1rem;">
                    <option value="">-- CREATE NEW PROJECT --</option>
                    <?php foreach($allProjects as $pid_opt => $label_opt): ?>
                        <option value="<?= $pid_opt ?>" <?= (isset($_GET['edit_id']) && $_GET['edit_id'] == $pid_opt) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label_opt) ?> (ID: <?= $pid_opt ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <div class="admin-section">
            <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #ccc; padding-bottom:10px; margin-bottom:20px;">
                <h2 style="margin:0;"><?= $editMode ? "EDIT: " . htmlspecialchars($slug) : "NEW PROJECT" ?></h2>
                <?php if($editMode): ?>
                    <form method="post" onsubmit="return confirm('DELETE PROJECT FOREVER?');">
                        <input type="hidden" name="delete_project" value="1">
                        <input type="hidden" name="project_id" value="<?= $pid ?>">
                        <input type="hidden" name="project_slug_hidden" value="<?= $slug ?>">
                        <button type="submit" class="btn btn-danger">DELETE</button>
                    </form>
                <?php endif; ?>
            </div>

            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="save_project" value="1">
                <?php if($editMode): ?><input type="hidden" name="project_id" value="<?= $pid ?>"><?php endif; ?>
                
                <label>PROJECT SLUG (ID)</label>
                <input type="text" name="project_slug" value="<?= $editMode ? htmlspecialchars($slug) : '' ?>" required <?= $editMode ? 'readonly style="background:#eee"' : '' ?>>

                <h3>TEXTS</h3>
                <?php foreach(['nom'=>'TITLE', 'resume'=>'SUMMARY', 'details'=>'DETAILS'] as $key => $lbl): ?>
                    <div style="margin-bottom:15px;">
                        <label style="color:var(--primary);"><?= $lbl ?></label>
                        <?php foreach($availableLangs as $lang): ?>
                            <input type="text" name="<?= $key ?>_<?= $lang ?>" value="<?= htmlspecialchars($editMode ? ($currentTexts[$key][$lang] ?? '') : '') ?>" placeholder="<?= strtoupper($lang) ?>">
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

                <h3>IMAGES (MIN 2)</h3>
                <div id="img-container"></div>
                <button type="button" class="btn btn-mini" onclick="addImg()">+ ADD IMAGE</button>

                <h3 style="margin-top:20px;">LINKS</h3>
                <div id="link-container"></div>
                <button type="button" class="btn btn-mini" onclick="addLink()">+ ADD LINK</button>

                <h3 style="margin-top:20px;">TECHNOLOGIES</h3>
                <div class="techno-grid">
                    <?php foreach($technosList as $tName): ?>
                        <label class="techno-item">
                            <input type="checkbox" name="technos[]" value="<?= htmlspecialchars($tName) ?>" <?= isset($currentTechnos[$tName]) ? 'checked' : '' ?>>
                            <?= htmlspecialchars($tName) ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="btn" style="width:100%; margin-top:30px; font-size:1.2rem;"><?= $editMode ? "UPDATE PROJECT" : "PUBLISH PROJECT" ?></button>
            </form>
        </div>
    </main>

    <script>
        const langs = <?= json_encode($availableLangs) ?>;
        const currentImages = <?= json_encode($editMode ? ($currentImages ?: new stdClass()) : new stdClass()) ?>;
        const currentTexts = <?= json_encode($editMode ? ($currentTexts ?: new stdClass()) : new stdClass()) ?>;
        const currentLinks = <?= json_encode($editMode ? $currentLinks : []) ?>;

        let imgCount = 0; let linkCount = 0;

        function addImg(label = '', path = '') {
            let lblVal = label ? `value="${label}"` : `value="${imgCount===0 && !label ? 'miniature' : ''}"`;
            let preview = path ? `<small style="color:green; display:block;">Current: ${path}</small><input type="hidden" name="img_paths_existing[${imgCount}]" value="${path}">` : '';
            let capInputs = langs.map(l => {
                let val = (label && currentTexts[`img_${label}_caption`] && currentTexts[`img_${label}_caption`][l]) ? currentTexts[`img_${label}_caption`][l] : '';
                return `<input type="text" name="img_caption_${imgCount}_${l}" value="${val}" placeholder="Alt ${l.toUpperCase()}" style="margin-bottom:5px;">`;
            }).join('');
            document.getElementById('img-container').insertAdjacentHTML('beforeend', `
                <div class="dynamic-row">
                    <div class="col"><label>Label</label><input type="text" name="img_labels[${imgCount}]" ${lblVal}>${preview}</div>
                    <div class="col"><label>File (PNG/WebP)</label><input type="file" name="imgs[${imgCount}]" accept=".webp,.png,.jpg,.jpeg"></div>
                    <div class="col"><label>Captions</label>${capInputs}</div>
                </div>`);
            imgCount++;
        }

        function addLink(label='', url='') {
            let btnInputs = langs.map(l => {
                let val = '';
                if (label && currentTexts[`link_${label}`] && currentTexts[`link_${label}`][l]) {
                    val = currentTexts[`link_${label}`][l];
                }
                return `<input type="text" name="links[${linkCount}][text][${l}]" value="${val}" placeholder="Btn ${l.toUpperCase()}" style="margin-bottom:5px;">`;
            }).join('');

            // Note l'ajout de "required" sur les inputs Label et URL ci-dessous
            document.getElementById('link-container').insertAdjacentHTML('beforeend', `
                <div class="dynamic-row">
                    <div class="col">
                        <label>Label *</label>
                        <input type="text" name="links[${linkCount}][label]" value="${label}" required placeholder="ex: github">
                    </div>
                    <div class="col">
                        <label>URL *</label>
                        <input type="url" name="links[${linkCount}][url]" value="${url}" required placeholder="https://...">
                    </div>
                    <div class="col">
                        <label>Button Text</label>
                        ${btnInputs}
                    </div>
                </div>`);
            linkCount++;
        }

        <?php if($editMode): ?>
            Object.entries(currentImages).forEach(([lbl, path]) => addImg(lbl, path));
            currentLinks.forEach(l => addLink(l.label, l.url));
            if(Object.keys(currentImages).length === 0) { addImg('miniature'); addImg(); }
        <?php else: ?>
            addImg('miniature'); addImg(); addLink();
        <?php endif; ?>
    </script>
</body>
</html>