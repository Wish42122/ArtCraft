<svg viewBox="0 0 560 260" preserveAspectRatio="xMidYMid meet" role="img" aria-label="Monthly artwork submissions">
    <defs>
        <linearGradient id="areaGrad" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#25c364" stop-opacity="0.35"/>
            <stop offset="100%" stop-color="#25c364" stop-opacity="0.02"/>
        </linearGradient>
    </defs>
    <g font-family="Inter, sans-serif" font-size="11" fill="#94a3b8">
        <?php for ($tick=0; $tick<=5; $tick++): $y=220-$tick*40; ?>
            <line x1="40" y1="<?=$y?>" x2="540" y2="<?=$y?>" stroke="#eef2f6" stroke-width="1"/>
            <text x="30" y="<?=$y+4?>" text-anchor="end"><?=e((int)round($maximum*$tick/5))?></text>
        <?php endfor; ?>
    </g>
    <path d="<?=e($areaPath)?>" fill="url(#areaGrad)"/>
    <path d="<?=e($linePath)?>" fill="none" stroke="#25c364" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
    <?php foreach ($points as $point): ?>
        <circle cx="<?=e($point['x'])?>" cy="<?=e($point['y'])?>" r="5" fill="#fff" stroke="#25c364" stroke-width="3"><title><?=e($point['label'])?>: <?=e($point['count'])?></title></circle>
        <text x="<?=e($point['x'])?>" y="245" text-anchor="middle" font-family="Inter, sans-serif" font-size="11" fill="#94a3b8"><?=e($point['label'])?></text>
    <?php endforeach; ?>
</svg>
