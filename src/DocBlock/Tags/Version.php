<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.3
 *
 * @author    Vasil Rangelov <boen.robot@gmail.com>
 * @copyright 2010-2011 Mike van Riel / Naenius (http://www.naenius.com)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace phpDocumentor\Reflection\DocBlock\Tags;

use phpDocumentor\Reflection\DocBlock\Tag;

/**
 * Reflection class for a @version tag in a Docblock.
 */
class Version extends Tag
{
    /**
     * PCRE regular expression matching a version vector.
     * Assumes the "x" modifier.
     */
    const REGEX_VECTOR = '(?:
        # Normal release vectors.
        \d\S*
        |
        # VCS version vectors. Per PHPCS, they are expected to
        # follow the form of the VCS name, followed by ":", followed
        # by the version vector itself.
        # By convention, popular VCSes like CVS, SVN and GIT use "$"
        # around the actual version vector.
        [^\s\:]+\:\s*\$[^\$]+\$
    )';

    /** @var string The version vector. */
    private $version = '';

    public function __construct($version, Description $description = null)
    {
        $this->version = $version;
        $this->description = $description;
    }

    /**
     * {@inheritdoc}
     */
    public static function create($content, Context $context = null)
    {
        $matches = [];
        if (!preg_match('/^(' . self::REGEX_VECTOR . ')\s*(.+)?$/sux', $content, $matches)) {
            return null;
        }

        return new static(
            $matches[1],
            new Description(isset($matches[2]) ? $matches[2] : '', $context)
        );
    }

    /**
     * Gets the version section of the tag.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Returns a string representation for this tag.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->version . ' ' . $this->description->render();
    }
}