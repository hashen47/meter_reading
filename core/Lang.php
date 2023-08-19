<?php
$path = parse_url($_SERVER["REQUEST_URI"])["path"];

if (isset($_REQUEST["lang"])) {
    $lang = $_REQUEST["lang"];
} else {
    $lang = "eng";
}
// echo "$lang";
?>

<?php if ($lang == "eng"): ?>
<div>
    <button>
        <a href="<?= $path . '?lang=sin'; ?>">Sinhala</a>
    </button>
    <button>
        <a href="<?= $path . '?lang=eng'; ?>">English
        </a>
    </button>
</div>
<?php elseif ($lang == "sin"): ?>
<div>
    <button>
        <a href="<?= $path . '?lang=sin'; ?>">සිංහල</a>
    </button>
    <button>
        <a href="<?= $path . '?lang=eng'; ?>">ඉංග්රීසි</a>
    </button>
</div>
<?php endif; ?>