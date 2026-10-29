<?php
namespace Lang\Css\Rule;
/**
 * Class Number — восстановление CSS-значений из pipe-формата и извлечение чисел.
 *
 * Входной формат (pipe-формат):
 *   - Имена функций заменены алиасами (calc→c, clamp→cp, ...)
 *   - Круглые скобки удалены
 *   - Запятые заменены на |
 *   - Операторы (+,-,*,/) остаются inline
 *   - Алиас — отдельный токен после |
 *
 * Пример: clamp(1rem, 2.5vw, 3rem) → cp|1rem|2.5vw|3rem
 *         calc(100% - 20px)       → c|100%-20px
 *         calc(100% - -20px)      → c|100%--20px
 */

class Number
{
    private const ALIASES = [
        // 3-буквенные
        'skx' => ['skewX', 1, 1],
        'sky' => ['skewY', 1, 1],
        // 2-буквенные
        'cp' => ['clamp', 3, 3],
        'mn' => ['min', 1, -1],
        'mx' => ['max', 1, -1],
        'mm' => ['minmax', 2, 2],
        'rt' => ['repeat', 2, -1],
        'sn' => ['sin', 1, 1],
        'rn' => ['round', 1, 3],
        'cs' => ['cos', 1, 1],
        'sq' => ['sqrt', 1, 1],
        'a2' => ['atan2', 2, 2],
        'ex' => ['exp', 1, 1],
        'ft' => ['fit-content', 1, 1],
        'sg' => ['sign', 1, 1],
        'tn' => ['tan', 1, 1],
        'pw' => ['pow', 2, 2],
        'rm' => ['rem', 2, 2],
        'hr' => ['hue-rotate', 1, 1],
        'sk' => ['skew', 1, 2],
        'rd' => ['rotate3d', 4, 4],
        'rx' => ['rotateX', 1, 1],
        'ry' => ['rotateY', 1, 1],
        'rz' => ['rotateZ', 1, 1],
        'sd' => ['scale3d', 3, 3],
        'sx' => ['scaleX', 1, 1],
        'sy' => ['scaleY', 1, 1],
        'sz' => ['scaleZ', 1, 1],
        'td' => ['translate3d', 3, 3],
        'tx' => ['translateX', 1, 1],
        'ty' => ['translateY', 1, 1],
        'tz' => ['translateZ', 1, 1],
        'bs' => ['brightness', 1, 1],
        'ct' => ['contrast', 1, 1],
        'sa' => ['sepia', 1, 1],
        'se' => ['saturate', 1, 1],
        'm3' => ['matrix3d', 16, 16],
        'mr' => ['matrix', 6, 6],
        // 1-буквенные
        'a' => ['abs', 1, 1],
        'b' => ['blur', 1, 1],
        'c' => ['calc', 1, -1],
        'd' => ['drop-shadow', 2, 4],
        'e' => ['env', 1, 2],
        'g' => ['grayscale', 1, 1],
        'h' => ['hypot', 1, -1],
        'i' => ['invert', 1, 1],
        'l' => ['log', 1, 2],
        'm' => ['mod', 2, 2],
        'o' => ['opacity', 1, 1],
        'p' => ['perspective', 1, 1],
        'r' => ['rotate', 1, 1],
        's' => ['scale', 1, -1],
        't' => ['translate', 1, -1],
        'v' => ['var', 1, 2],
    ];

    private static function aliasList(): array
    {
        static $list = null;
        if ($list === null) {
            $list = array_keys(self::ALIASES);
            usort($list, fn($a, $b) => strlen($b) <=> strlen($a));
        }
        return $list;
    }

    private static function isAlias(string $token): bool
    {
        return isset(self::ALIASES[$token]);
    }

    private static function isValidRest(string $rest, string $alias): bool
    {
        if ($rest === '') return true;
        if (str_starts_with($rest, '--')) return true;
        if (preg_match('/^-?\d/', $rest) || preg_match('/^\.\d/', $rest)) return true;
        foreach (self::aliasList() as $a) {
            if (strlen($a) >= 2 && str_starts_with($rest, $a)) return true;
        }
        if (strlen($alias) >= 2) return true;
        return false;
    }

    private static function checkAliasPrefix(string $token): array
    {
        if ($token === '') return [$token];
        foreach (self::aliasList() as $alias) {
            if (str_starts_with($token, $alias)) {
                $rest = substr($token, strlen($alias));
                if ($rest === '') return [$alias];
                if (self::isValidRest($rest, $alias)) return [$alias, $rest];
            }
        }
        return [$token];
    }

    private static function splitInline(string $token): array
    {
        if ($token === '' || str_starts_with($token, '--')) return [$token];
        if (preg_match('/^[a-zA-Z][a-zA-Z0-9_-]*$/', $token) && !preg_match('/\d$/', $token)) {
            return self::checkAliasPrefix($token);
        }
        $result = [];
        $current = '';
        $len = strlen($token);
        $i = 0;
        while ($i < $len) {
            $ch = $token[$i];
            if ($ch === '*' || $ch === '/') {
                if ($current !== '') {
                    array_push($result, ...self::checkAliasPrefix($current));
                    $current = '';
                }
                $result[] = $ch;
                $i++;
            } elseif ($ch === '+') {
                if ($current !== '') {
                    array_push($result, ...self::checkAliasPrefix($current));
                    $current = '';
                }
                $result[] = $ch;
                $i++;
            } elseif ($ch === '-') {
                if ($current !== '' && preg_match('/\d+(?:\.\d+)?(?:[eE][+-]?\d+)?\s*(?:px|rem|em|ex|ch|rh|rlh|vh|vw|vmin|vmax|svh|lvh|dvh|svw|lvw|dvw|vb|vi|fr|%|deg|rad|grad|turn|s|ms|pt|pc|in|cm|mm|Q|dpi|dpcm|dppx|Hz|kHz)?$/i', $current)) {
                    array_push($result, ...self::checkAliasPrefix($current));
                    $current = '';
                    $result[] = '-';
                    $i++;
                } else {
                    $current .= $ch;
                    $i++;
                }
            } else {
                $current .= $ch;
                $i++;
            }
        }
        if ($current !== '') {
            array_push($result, ...self::checkAliasPrefix($current));
        }
        return $result;
    }

    /**
     * Восстановить полное CSS-значение из pipe-формата.
     */
    public function restore(string $value): string
    {
        $value = trim($value);
        if ($value === '') return '';
        $tokens = explode('|', $value);
        $results = [];
        $i = 0;
        $count = count($tokens);
        while ($i < $count) {
            if (self::isAlias($tokens[$i])) {
                [$text, $consumed] = $this->parseFn($tokens, $i, $count - $i - 1);
                $results[] = $text;
                $i += 1 + $consumed;
            } else {
                [$text, $extra] = $this->parseValue($tokens[$i], $tokens, $i, $count - $i);
                $results[] = $text;
                $i += 1 + $extra;
            }
        }
        return implode(' ', $results);
    }

    /**
     * Извлечь все числовые значения из обычного CSS-текста.
     */
    public function extractNumbers(string $css): array
    {
        $NUM = '-?\d+(?:\.\d+)?(?:[eE][+-]?\d+)?';
        $UNIT = '(?:px|rem|em|ex|ch|rh|rlh|vh|vw|vmin|vmax|svh|lvh|dvh|svw|lvw|dvw|vb|vi|fr|%|deg|rad|grad|turn|s|ms|pt|pc|in|cm|mm|Q|dpi|dpcm|dppx|Hz|kHz)';
        $pattern = '/' . $NUM . '\s*' . $UNIT . '(?![a-zA-Z])|(?<![a-zA-Z0-9._-])' . $NUM . '(?![a-zA-Z0-9.%])/i';
        preg_match_all($pattern, $css, $matches);
        return $matches[0];
    }

    private function parseFn(array $tokens, int $idx, int $budget): array
    {
        $alias = $tokens[$idx];
        [$fullName, $minA, $maxA] = self::ALIASES[$alias];
        $variadic = ($maxA === -1);
        $inCalc = ($alias === 'c');
        $args = [];
        $consumed = 0;
        $i = $idx + 1;
        $count = count($tokens);
        while ($i < $count && $consumed < $budget) {
            if (!$variadic && count($args) >= $maxA) break;
            if (self::isAlias($tokens[$i])) {
                $fnBudget = $budget - $consumed - 1;
                if (!$variadic) {
                    $stillNeed = $maxA - count($args) - 1;
                    $fnBudget = max(0, $fnBudget - $stillNeed);
                }
                [$fnText, $fnConsumed] = $this->parseFn($tokens, $i, $fnBudget);
                $args[] = $fnText;
                $consumed += 1 + $fnConsumed;
                $i += 1 + $fnConsumed;
            } else {
                [$valText, $valExtra] = $this->parseValue($tokens[$i], $tokens, $i, $budget - $consumed);
                $args[] = $valText;
                $consumed += 1 + $valExtra;
                $i += 1 + $valExtra;
            }
        }
        $sep = $inCalc ? ' ' : ', ';
        if ($fullName === 'env' && !empty($args) && str_starts_with($args[0], '--')) {
            $args[0] = substr($args[0], 2);
        }
        return [$fullName . '(' . implode($sep, $args) . ')', $consumed];
    }

    private function parseValue(string $token, array $tokens, int $idx, int $remainingBudget): array
    {
        $subTokens = self::splitInline($token);
        $hasAlias = false;
        foreach ($subTokens as $st) {
            if (self::isAlias($st)) { $hasAlias = true; break; }
        }
        if (!$hasAlias) {
            if (count($subTokens) === 1) return [$subTokens[0], 0];
            return [implode(' ', $subTokens), 0];
        }
        $parts = [];
        $extra = 0;
        $budget = $remainingBudget - 1;
        $j = 0;
        $subCount = count($subTokens);
        while ($j < $subCount) {
            $st = $subTokens[$j];
            if (self::isAlias($st)) {
                [$fullName, $minA, $maxA] = self::ALIASES[$st];
                $variadic = ($maxA === -1);
                $args = [];
                $k = $j + 1;
                while ($k < $subCount && ($variadic || count($args) < $maxA)) {
                    $inlineSt = $subTokens[$k];
                    if (in_array($inlineSt, ['+', '-', '*', '/'], true)) break;
                    $args[] = $inlineSt;
                    $k++;
                }
                $ti = $idx + 1 + $extra;
                $count = count($tokens);
                while (($variadic || count($args) < $maxA) && $ti < $count && $budget - $extra > 0) {
                    if (!$variadic) {
                        $stillNeed = $maxA - count($args);
                        if ($budget - $extra < $stillNeed) break;
                    }
                    if (self::isAlias($tokens[$ti])) {
                        $fnBudget = $budget - $extra - 1;
                        if (!$variadic) {
                            $stillNeedAfter = $maxA - count($args) - 1;
                            $fnBudget = max(0, $fnBudget - $stillNeedAfter);
                        }
                        [$fnText, $fnConsumed] = $this->parseFn($tokens, $ti, $fnBudget);
                        $args[] = $fnText;
                        $extra += 1 + $fnConsumed;
                        $ti += 1 + $fnConsumed;
                    } else {
                        [$valText, $valExtra] = $this->parseValue($tokens[$ti], $tokens, $ti, $budget - $extra);
                        $args[] = $valText;
                        $extra += 1 + $valExtra;
                        $ti += 1 + $valExtra;
                    }
                }
                $inCalc = ($st === 'c');
                $sep = $inCalc ? ' ' : ', ';
                if ($fullName === 'env' && !empty($args) && str_starts_with($args[0], '--')) {
                    $args[0] = substr($args[0], 2);
                }
                $parts[] = $fullName . '(' . implode($sep, $args) . ')';
                $j = $k;
            } else {
                $parts[] = $st;
                $j++;
            }
        }
        return [implode(' ', $parts), $extra];
    }
}

class NumberTester
{
    private Number $n;
    private int $passed = 0;
    private int $failed = 0;
    private array $failures = [];

    public function __construct()
    {
        $this->n = new Number();
    }

    public function test(string $input, string $expected): void
    {
        $actual = $this->n->restore($input);
        $num = $this->passed + $this->failed + 1;
        if ($actual === $expected) {
            $this->passed++;
        } else {
            $this->failed++;
            $this->failures[] = [
                'num'     => $num,
                'input'   => $input,
                'expected'=> $expected,
                'actual'  => $actual,
            ];
        }
    }

    public function testExtract(string $css, array $expected): void
    {
        $actual = $this->n->extractNumbers($css);
        $num = $this->passed + $this->failed + 1;
        if ($actual === $expected) {
            $this->passed++;
        } else {
            $this->failed++;
            $this->failures[] = [
                'num'     => $num,
                'input'   => $css,
                'expected'=> implode(', ', $expected),
                'actual'  => implode(', ', $actual),
            ];
        }
    }

    public function run(): void
    {
        // === restore() тесты ===

        // Простые значения
        $this->test('16px', '16px');
        $this->test('2.5vw', '2.5vw');
        $this->test('100%', '100%');
        $this->test('auto', 'auto');
        $this->test('auto-fit', 'auto-fit');

        // Верхний уровень (разделитель — пробел)
        $this->test('1fr|2fr|1fr', '1fr 2fr 1fr');
        $this->test('10px|20px|30px', '10px 20px 30px');
        $this->test('center|10px|20px', 'center 10px 20px');

        // calc — операторы inline, разделитель пробел
        $this->test('c|100%-20px', 'calc(100% - 20px)');
        $this->test('c|100%--20px', 'calc(100% - -20px)');
        $this->test('c|100%-20px+10px', 'calc(100% - 20px + 10px)');
        $this->test('c|100%*2', 'calc(100% * 2)');
        $this->test('c|100%/2', 'calc(100% / 2)');
        $this->test('c|100%--20px', 'calc(100% - -20px)');

        // clamp
        $this->test('cp|1rem|2.5vw|3rem', 'clamp(1rem, 2.5vw, 3rem)');
        $this->test('cp|16px|2.67px+1.11vw|24px', 'clamp(16px, 2.67px + 1.11vw, 24px)');

        // calc внутри clamp
        $this->test('cp|c|1rem*2|2vw|3rem', 'clamp(calc(1rem * 2), 2vw, 3rem)');
        $this->test('cp|1rem|c|100vw-2|3rem', 'clamp(1rem, calc(100vw - 2), 3rem)');

        // min / max
        $this->test('mn|100px|50vw|200px', 'min(100px, 50vw, 200px)');
        $this->test('mx|100px|50vw|200px', 'max(100px, 50vw, 200px)');
        $this->test('mn|100px|50vw-20px|200px', 'min(100px, 50vw - 20px, 200px)');

        // minmax / repeat (grid)
        $this->test('mm|200px|1fr', 'minmax(200px, 1fr)');
        $this->test('rt|auto-fit|mm|200px|1fr', 'repeat(auto-fit, minmax(200px, 1fr))');
        $this->test('rt|3|mm|200px|1fr', 'repeat(3, minmax(200px, 1fr))');

        // var с fallback
        $this->test('v|--w', 'var(--w)');
        $this->test('v|--w|16px', 'var(--w, 16px)');
        $this->test('v|--w|c|100vw-2', 'var(--w, calc(100vw - 2))');
        $this->test('v|--w|v|--margin|16px', 'var(--w, var(--margin, 16px))');

        // Вложенные функции
        $this->test('c|v--w', 'calc(var(--w))');
        $this->test('c|100%-v|--margin|16px', 'calc(100% - var(--margin, 16px))');
        $this->test('c|v|c|100vw-20px', 'calc(var(calc(100vw - 20px)))');

        // Математические функции
        $this->test('a|-5', 'abs(-5)');
        $this->test('sn|0.5', 'sin(0.5)');
        $this->test('cs|0.5', 'cos(0.5)');
        $this->test('tn|0.5', 'tan(0.5)');
        $this->test('sq|4', 'sqrt(4)');
        $this->test('pw|2|3', 'pow(2, 3)');
        $this->test('a2|1', 'atan2(1)');
        $this->test('ex|1', 'exp(1)');
        $this->test('sg|-1', 'sign(-1)');
        $this->test('h|3|4', 'hypot(3, 4)');
        $this->test('l|10', 'log(10)');
        $this->test('l|10|2', 'log(10, 2)');
        $this->test('m|10|3', 'mod(10, 3)');
        $this->test('rm|10|3', 'rem(10, 3)');
        $this->test('rn|1|2|3', 'round(1, 2, 3)');

        // Округление с модой
        $this->test('rn|1.5|up|1', 'round(1.5, up, 1)');
        $this->test('rn|2.5|down|1', 'round(2.5, down, 1)');
        $this->test('rn|3.7|to-zero|1', 'round(3.7, to-zero, 1)');

        // Вложенные математические
        $this->test('cs|sq|4', 'cos(sqrt(4))');
        $this->test('sg|a|-5', 'sign(abs(-5))');
        $this->test('cs|sq|4+1', 'cos(sqrt(4) + 1)');
        $this->test('c|a|-5*2', 'calc(abs(-5) * 2)');
        $this->test('cp|a|-5|mn|10|20', 'clamp(abs(-5), min(10), 20)');

        // env
        $this->test('e|--safe-area-inset-top', 'env(safe-area-inset-top)');
        $this->test('e|--safe-area-inset-top|20px', 'env(safe-area-inset-top, 20px)');

        // fit-content
        $this->test('ft|200px', 'fit-content(200px)');
        $this->test('ft|200px|300px|400px', 'fit-content(200px, 300px, 400px)');

        // === Фильтры ===
        $this->test('b|5px', 'blur(5px)');
        $this->test('bs|1.5', 'brightness(1.5)');
        $this->test('ct|200%', 'contrast(200%)');
        $this->test('g|100%', 'grayscale(100%)');
        $this->test('hr|90deg', 'hue-rotate(90deg)');
        $this->test('i|100%', 'invert(100%)');
        $this->test('sa|80%', 'sepia(80%)');
        $this->test('se|2', 'saturate(2)');
        $this->test('d|10px|5px|red', 'drop-shadow(10px, 5px, red)');
        $this->test('d|10px|5px|10px|red', 'drop-shadow(10px, 5px, 10px, red)');
        $this->test('o|0.5', 'opacity(0.5)');

        // === Трансформации ===
        $this->test('r|45deg', 'rotate(45deg)');
        $this->test('rd|1|0|0|1', 'rotate3d(1, 0, 0, 1)');
        $this->test('rx|45deg', 'rotateX(45deg)');
        $this->test('ry|45deg', 'rotateY(45deg)');
        $this->test('rz|45deg', 'rotateZ(45deg)');

        $this->test('s|2', 'scale(2)');
        $this->test('s|2|3', 'scale(2, 3)');
        $this->test('s|2|3|4', 'scale(2, 3, 4)');
        $this->test('sd|1|2|3', 'scale3d(1, 2, 3)');
        $this->test('sx|2', 'scaleX(2)');
        $this->test('sy|2', 'scaleY(2)');
        $this->test('sz|2', 'scaleZ(2)');

        $this->test('t|10px', 'translate(10px)');
        $this->test('t|10px|20px', 'translate(10px, 20px)');
        $this->test('td|10px|20px|30px', 'translate3d(10px, 20px, 30px)');
        $this->test('tx|10px', 'translateX(10px)');
        $this->test('ty|10px', 'translateY(10px)');
        $this->test('tz|10px', 'translateZ(10px)');

        $this->test('sk|30deg', 'skew(30deg)');
        $this->test('sk|30deg|15deg', 'skew(30deg, 15deg)');
        $this->test('skx|30deg', 'skewX(30deg)');
        $this->test('sky|30deg', 'skewY(30deg)');

        $this->test('p|1000px', 'perspective(1000px)');

        // matrix
        $this->test('mr|1|0|0|1|0|0', 'matrix(1, 0, 0, 1, 0, 0)');

        // Трансформации с вложенными функциями
        $this->test('r|c|45deg', 'rotate(calc(45deg))');
        $this->test('r|c|45deg+5deg', 'rotate(calc(45deg + 5deg))');
        $this->test('t|10px|c|20px+5px', 'translate(10px, calc(20px + 5px))');
        $this->test('r|tn|n', 'rotate(tan(n))');

        // Не алиасы (red, auto-fit и т.д.)
        $this->test('red', 'red');
        $this->test('blue', 'blue');
        $this->test('auto-fit', 'auto-fit');
        $this->test('auto-fill', 'auto-fill');
        $this->test('d|10px|5px|10px|rgba(0,0,0,0.5)', 'drop-shadow(10px, 5px, 10px, rgba(0,0,0,0.5))');

        // Сложная вложенность
        $this->test('c|100%-2*v--margin|16px', 'calc(100% - 2 * var(--margin, 16px))');
        $this->test('cp|1rem|c|100vw-2*v--margin|16px|3rem', 'clamp(1rem, calc(100vw - 2 * var(--margin, 16px)), 3rem)');
        $this->test('mx|100px|mn|50px|200px', 'max(100px, min(50px, 200px))');
        $this->test('mn|100px|mx|50vw|200px', 'min(100px, max(50vw, 200px))');

        // === extractNumbers() тесты ===
        $this->testExtract('width: calc(100% - 20px);', ['100%', '20px']);
        $this->testExtract('font-size: clamp(1rem, 2.5vw, 3rem);', ['1rem', '2.5vw', '3rem']);
        $this->testExtract('gap: 16px;', ['16px']);
        $this->testExtract('transform: rotate(45deg) translate(10px, 20px);', ['45deg', '10px', '20px']);
        $this->testExtract('grid-template-columns: repeat(3, minmax(200px, 1fr));', ['3', '200px', '1fr']);

        // Вывод результатов
        echo str_repeat('=', 60) . "\n";
        echo "Тесты restore() и extractNumbers()\n";
        echo str_repeat('=', 60) . "\n";
        echo '<pre>';
        echo "Пройдено: {$this->passed}\n";
        echo "Провалено: {$this->failed}\n";
        echo '</pre>';

        if (!empty($this->failures)) {
            echo "\n--- ОШИБКИ ---\n";
            foreach ($this->failures as $f) {
                echo '<pre>';
                echo "\nТест #{$f['num']}\n";
                echo "  Вход:       {$f['input']}\n";
                echo "  Ожидалось:  {$f['expected']}\n";
                echo "  Получено:   {$f['actual']}\n";
                echo '</pre>';
            }
        } else {
            echo "\nВсе тесты пройдены! ✅\n";
        }
    }
}


$tests = [
    ["16px", "16px"],
    ["auto", "auto"],
    ["auto-fit", "auto-fit"],
    ["red", "red"],
    ["blue", "blue"],
    ["1fr|2fr|1fr", "1fr 2fr 1fr"],
    ["c|100%-20px", "calc(100% - 20px)"],
    ["c|100%--20px", "calc(100% - -20px)"],
    ["c|100%*2", "calc(100% * 2)"],
    ["c|100%/2", "calc(100% / 2)"],
    ["c|100%+20px", "calc(100% + 20px)"],
    ["c|100%-20px+10px", "calc(100% - 20px + 10px)"],
    ["cp|16px|2.67px+1.11vw|24px", "clamp(16px, 2.67px + 1.11vw, 24px)"],
    ["cp|1rem|2.5vw|3rem", "clamp(1rem, 2.5vw, 3rem)"],
    ["cp|c|1rem*2|2vw|3rem", "clamp(calc(1rem * 2), 2vw, 3rem)"],
    ["cp|c|1rem*pw|2|3|2vw|3rem", "clamp(calc(1rem * pow(2, 3)), 2vw, 3rem)"],
    ["mn|100px|50vw-20px|200px", "min(100px, 50vw - 20px, 200px)"],
    ["mx|100px|50vw+20px", "max(100px, 50vw + 20px)"],
    ["mn|100px|-20px", "min(100px, -20px)"],
    ["mn|100px|50vw-20px", "min(100px, 50vw - 20px)"],
    ["rt|auto-fit|mm|200px|1fr", "repeat(auto-fit, minmax(200px, 1fr))"],
    ["rt|auto-fill|mm|mn|200px|1fr|2fr", "repeat(auto-fill, minmax(min(200px, 1fr), 2fr))"],
    ["v|--w|c|100vw-2", "var(--w, calc(100vw - 2))"],
    ["v|--margin|16px", "var(--margin, 16px)"],
    ["v|--w|c|100vw-2*v|--margin|16px", "var(--w, calc(100vw - 2 * var(--margin, 16px)))"],
    ["cs|sq|4", "cos(sqrt(4))"],
    ["sg|a|-5", "sign(abs(-5))"],
    ["a|-5", "abs(-5)"],
    ["a|5", "abs(5)"],
    ["sq|4", "sqrt(4)"],
    ["sn|30deg", "sin(30deg)"],
    ["cs|30deg", "cos(30deg)"],
    ["tn|45deg", "tan(45deg)"],
    ["ex|2", "exp(2)"],
    ["l|10", "log(10)"],
    ["h|3|4", "hypot(3, 4)"],
    ["pw|2|3", "pow(2, 3)"],
    ["m|10|3", "mod(10, 3)"],
    ["rm|10|3", "rem(10, 3)"],
    ["a2|1|1", "atan2(1, 1)"],
    ["rn|1.5|up|1", "round(1.5, up, 1)"],
    ["rn|1.5", "round(1.5)"],
    ["rn|1.5|nearest", "round(1.5, nearest)"],
    ["e|--safe-area-inset-top", "env(safe-area-inset-top)"],
    ["e|--safe-area-inset-top|20px", "env(safe-area-inset-top, 20px)"],
    ["b|5px", "blur(5px)"],
    ["bs|1.5", "brightness(1.5)"],
    ["ct|0.8", "contrast(0.8)"],
    ["g|50%", "grayscale(50%)"],
    ["hr|90deg", "hue-rotate(90deg)"],
    ["i|75%", "invert(75%)"],
    ["sa|60%", "sepia(60%)"],
    ["se|2", "saturate(2)"],
    ["d|10px|5px|10px|red", "drop-shadow(10px, 5px, 10px, red)"],
    ["d|10px|5px|red", "drop-shadow(10px, 5px, red)"],
    ["o|0.5", "opacity(0.5)"],
    ["r|45deg", "rotate(45deg)"],
    ["rd|1|0|0|45deg", "rotate3d(1, 0, 0, 45deg)"],
    ["rx|45deg", "rotateX(45deg)"],
    ["ry|45deg", "rotateY(45deg)"],
    ["rz|45deg", "rotateZ(45deg)"],
    ["s|2", "scale(2)"],
    ["s|2|3", "scale(2, 3)"],
    ["sd|1|2|3", "scale3d(1, 2, 3)"],
    ["sx|2", "scaleX(2)"],
    ["sy|2", "scaleY(2)"],
    ["sz|2", "scaleZ(2)"],
    ["sk|30deg|20deg", "skew(30deg, 20deg)"],
    ["skx|30deg", "skewX(30deg)"],
    ["sky|20deg", "skewY(20deg)"],
    ["t|50px|100px", "translate(50px, 100px)"],
    ["t|50px", "translate(50px)"],
    ["td|10px|20px|30px", "translate3d(10px, 20px, 30px)"],
    ["tx|50px", "translateX(50px)"],
    ["ty|100px", "translateY(100px)"],
    ["tz|50px", "translateZ(50px)"],
    ["p|1000px", "perspective(1000px)"],
    ["mr|1|0|0|1|0|0", "matrix(1, 0, 0, 1, 0, 0)"],
    ["m3|1|0|0|0|0|1|0|0|0|0|1|0|0|0|0|1", "matrix3d(1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1)"],
    ["cp|1rem|c|100vw-2*v|--margin|16px|3rem", "clamp(1rem, calc(100vw - 2 * var(--margin, 16px)), 3rem)"],
    ["c|mn|100px|50vw", "calc(min(100px, 50vw))"],
    ["c|mx|100px|50vw-20px", "calc(max(100px, 50vw - 20px))"],
    ["v|--w|c|100vw-c|50%-20px", "var(--w, calc(100vw - calc(50% - 20px)))"],
    ["nearest", "nearest"],
    ["up", "up"],
];

$n = new Number();
$passed = 0;
$failed = 0;

foreach ($tests as $i => [$inp, $expected]) {
    $result = $n->restore($inp);
    if ($result === $expected) {
        $passed++;
    } else {
        echo '<pre>';
        $failed++;
        echo "FAIL #" . ($i + 1) . ": $inp\n";
        echo "  Expected: $expected\n";
        echo "  Got:      $result\n";
        echo '</pre>';
    }
}

// extractNumbers tests
$cssTests = [
    ["width: calc(100% - 20px);", ["100%", "20px"]],
    ["font-size: clamp(1rem, 2.5vw, 3rem);", ["1rem", "2.5vw", "3rem"]],
    ["margin: 10px 20px;", ["10px", "20px"]],
    ["transform: rotate(45deg) scale(2);", ["45deg", "2"]],
    ["color: rgba(255, 0, 0, 0.5);", ["255", "0", "0", "0.5"]],
];

foreach ($cssTests as $i => [$css, $expected]) {
    $result = $n->extractNumbers($css);
    if ($result === $expected) {
        $passed++;
    } else {
        $failed++;
        echo '<pre>';
        echo "FAIL CSS #" . ($i + 1) . ": $css\n";
        echo "  Expected: " . implode(", ", $expected) . "\n";
        echo "  Got:      " . implode(", ", $result) . "\n";
        echo '</pre>';
    }
}

echo "\n$passed passed, $failed failed out of " . (count($tests) + count($cssTests)) . "\n";


$tester = new NumberTester();
$tester->run();