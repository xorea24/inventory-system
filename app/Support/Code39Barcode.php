<?php

namespace App\Support;

class Code39Barcode
{
    private const MAP = [
        '0' => 'nnnwwnwnn', '1' => 'wnnwnnnnw', '2' => 'nnwwnnnnw', '3' => 'wnwwnnnnn',
        '4' => 'nnnwwnnnw', '5' => 'wnnwwnnnn', '6' => 'nnwwwnnnn', '7' => 'nnnwnnwnw',
        '8' => 'wnnwnnwnn', '9' => 'nnwwnnwnn', 'A' => 'wnnnnwnnw', 'B' => 'nnwnnwnnw',
        'C' => 'wnwnnwnnn', 'D' => 'nnnnwwnnw', 'E' => 'wnnnwwnnn', 'F' => 'nnwnwwnnn',
        'G' => 'nnnnnwwnw', 'H' => 'wnnnnwwnn', 'I' => 'nnwnnwwnn', 'J' => 'nnnnwwwnn',
        'K' => 'wnnnnnnww', 'L' => 'nnwnnnnww', 'M' => 'wnwnnnnwn', 'N' => 'nnnnwnnww',
        'O' => 'wnnnwnnwn', 'P' => 'nnwnwnnwn', 'Q' => 'nnnnnnwww', 'R' => 'wnnnnnwwn',
        'S' => 'nnwnnnwwn', 'T' => 'nnnnwnwwn', 'U' => 'wwnnnnnnw', 'V' => 'nwwnnnnnw',
        'W' => 'wwwnnnnnn', 'X' => 'nwnnwnnnw', 'Y' => 'wwnnwnnnn', 'Z' => 'nwwnwnnnn',
        '-' => 'nwnnnnwnw', '.' => 'wwnnnnwnn', ' ' => 'nwwnnnwnn', '$' => 'nwnwnwnnn',
        '/' => 'nwnwnnnwn', '+' => 'nwnnnwnwn', '%' => 'nnnwnwnwn', '*' => 'nwnnwnwnn',
    ];

    public static function svg(string $value, int $height = 72): string
    {
        $value = strtoupper($value);
        $encoded = '*'.$value.'*';
        $bars = [];
        $x = 10;

        foreach (str_split($encoded) as $character) {
            $pattern = self::MAP[$character] ?? self::MAP['-'];

            foreach (str_split($pattern) as $index => $width) {
                $barWidth = $width === 'w' ? 3 : 1;

                if ($index % 2 === 0) {
                    $bars[] = sprintf('<rect x="%d" y="10" width="%d" height="%d" fill="#111827" />', $x, $barWidth, $height);
                }

                $x += $barWidth;
            }

            $x += 1;
        }

        $svgWidth = $x + 10;
        $textY = $height + 30;
        $safeValue = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

        return sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 %d %d" role="img" aria-label="Barcode %s"><rect width="100%%" height="100%%" fill="#ffffff" />%s<text x="%d" y="%d" text-anchor="middle" font-family="monospace" font-size="14" fill="#111827">%s</text></svg>',
            $svgWidth,
            $height + 42,
            $svgWidth,
            $height + 42,
            $safeValue,
            implode('', $bars),
            (int) ($svgWidth / 2),
            $textY,
            $safeValue
        );
    }
}
