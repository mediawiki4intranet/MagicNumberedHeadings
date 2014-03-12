<?php
/**
 * @copyright Copyright © 2007, Purodha Blissenabch.
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation, version 2
 * of the License.
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * See the GNU General Public License for more details.
 */

/**
 * This extension implements new magic words:
 * __NUMBEREDHEADINGS__ and __NONUMBEREDHEADINGS__
 * If an article contains it, numbering of headings is performed
 * (or NOT performed) regardless of the user preference setting.
 *
 * How to use:
 * * include this extension in LocalSettings.php:
 *   require_once($IP.'/extensions/MagicNoNumberedHeadings.php');
 * * Add "__NUMBEREDHEADINGS__" to any article of your choice.
 *
 * @author Purodha Blissenbach, Vitaliy Filippov
 */

if (!defined('MEDIAWIKI'))
    die("This requires the MediaWiki enviroment.");

$wgExtensionCredits['parserhook'][] = array(
    'name'        => 'MagicNumberedHeadings',
    'version'     => '2014-03-13',
    'author'      => 'Purodha Blissenbach',
    'url'         => 'http://www.mediawiki.org/wiki/Extension:MagicNumberedHeadings',
    'description' => 'Add MagicWord "<nowiki>__NUMBEREDHEADINGS__</nowiki>".',
);
$wgHooks['MagicWordMagicWords'][] = 'MagicNumberedHeadingsMagicWordMagicWords';
$wgHooks['MagicWordwgVariableIDs'][] = 'MagicNumberedHeadingsMagicWordwgVariableIDs';
$wgHooks['LanguageGetMagic'][] = 'MagicNumberedHeadingsLanguageGetMagic';
$wgHooks['ParserBeforeInternalParse'][] = 'MagicNumberedHeadingsParserBeforeInternalParse';

function MagicNumberedHeadingsMagicWordMagicWords(&$magicWords)
{
    $magicWords[] = 'MAG_NUMBEREDHEADINGS';
    $magicWords[] = 'MAG_NONUMBEREDHEADINGS';
    return true;
}

function MagicNumberedHeadingsMagicWordwgVariableIDs(&$wgVariableIDs)
{
    $wgVariableIDs[] = 'MAG_NUMBEREDHEADINGS';
    $wgVariableIDs[] = 'MAG_NONUMBEREDHEADINGS';
    return true;
}

function MagicNumberedHeadingsLanguageGetMagic(&$magicWords, $langCode)
{
    switch($langCode)
    {
        case 'de':
            $magicWords['MAG_NUMBEREDHEADINGS'] = array(0, '__ÜBERSCHRIFTENNUMMERIERUNG__', '__NUMBEREDHEADINGS__');
            break;
        case 'ksh':
            $magicWords['MAG_NUMBEREDHEADINGS'] = array(0, '__ÖVVERSCHRIFTENUMMERIERE__', '__NUMBEREDHEADINGS__');
            break;
        default:
            $magicWords['MAG_NUMBEREDHEADINGS'] = array(0, '__NUMBEREDHEADINGS__');
            $magicWords['MAG_NONUMBEREDHEADINGS'] = array(0, '__NONUMBEREDHEADINGS__');
    }
    return true;
}

function MagicNumberedHeadingsParserBeforeInternalParse($parser, &$text, $stripState)
{
    if (MagicWord::get('MAG_NUMBEREDHEADINGS')->matchAndRemove($text))
        $parser->mOptions->mNumberHeadings = TRUE;
    if (MagicWord::get('MAG_NONUMBEREDHEADINGS')->matchAndRemove($text))
        $parser->mOptions->mNumberHeadings = FALSE;
    return true;
}
