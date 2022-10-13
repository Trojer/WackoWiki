<?php
/**
 * @package SimplePie
 * @copyright 2004-2016 Ryan Parman, Sam Sneddon, Ryan McCue
 * @author Ryan Parman
 * @author Sam Sneddon
 * @author Ryan McCue
 * @link http://simplepie.org/ SimplePie
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace SimplePie;

/**
 * Handles creating objects and calling methods
 *
 * Access this via {@see \SimplePie\SimplePie::get_registry()}
 *
 * @package SimplePie
 */
class Registry
{
    /**
     * Default class mapping
     *
     * Overriding classes *must* subclass these.
     *
     * @var array
     */
    protected $default = [
        'Cache' => Cache::class,
        'Locator' => Locator::class,
        'Parser' => Parser::class,
        'File' => File::class,
        'Sanitize' => Sanitize::class,
        'Item' => Item::class,
        'Author' => Author::class,
        'Category' => Category::class,
        'Enclosure' => Enclosure::class,
        'Caption' => Caption::class,
        'Copyright' => Copyright::class,
        'Credit' => Credit::class,
        'Rating' => Rating::class,
        'Restriction' => Restriction::class,
        'Content_Type_Sniffer' => Content\Type\Sniffer::class,
        'Source' => Source::class,
        'Misc' => Misc::class,
        'XML_Declaration_Parser' => XML\Declaration\Parser::class,
        'Parse_Date' => Parse\Date::class,
    ];

    /**
     * Class mapping
     *
     * @see register()
     * @var array
     */
    protected $classes = [];

    /**
     * Legacy classes
     *
     * @see register()
     * @var array
     */
    protected $legacy = [];

    /**
     * Constructor
     *
     * No-op
     */
    public function __construct()
    {
    }

    /**
     * Register a class
     *
     * @param string $type See {@see $default} for names
     * @param string $class Class name, must subclass the corresponding default
     * @param bool $legacy Whether to enable legacy support for this class
     * @return bool Successfulness
     */
    public function register($type, $class, $legacy = false)
    {
        if (!@is_subclass_of($class, $this->default[$type])) {
            return false;
        }

        $this->classes[$type] = $class;

        if ($legacy) {
            $this->legacy[] = $class;
        }

        return true;
    }

    /**
     * Get the class registered for a type
     *
     * Where possible, use {@see create()} or {@see call()} instead
     *
     * @param string $type
     * @return string|null
     */
    public function get_class($type)
    {
        if (!empty($this->classes[$type])) {
            return $this->classes[$type];
        }
        if (!empty($this->default[$type])) {
            return $this->default[$type];
        }

        return null;
    }

    /**
     * Create a new instance of a given type
     *
     * @param string $type
     * @param array $parameters Parameters to pass to the constructor
     * @return object Instance of class
     */
    public function &create($type, $parameters = [])
    {
        $class = $this->get_class($type);

        if (in_array($class, $this->legacy)) {
            switch ($type) {
                case 'locator':
                    // Legacy: file, timeout, useragent, file_class, max_checked_feeds, content_type_sniffer_class
                    // Specified: file, timeout, useragent, max_checked_feeds
                    $replacement = [$this->get_class('file'), $parameters[3], $this->get_class('content_type_sniffer')];
                    array_splice($parameters, 3, 1, $replacement);
                    break;
            }
        }

        if (!method_exists($class, '__construct')) {
            $instance = new $class();
        } else {
            $reflector = new \ReflectionClass($class);
            $instance = $reflector->newInstanceArgs($parameters);
        }

        if (method_exists($instance, 'set_registry')) {
            $instance->set_registry($this);
        }
        return $instance;
    }

    /**
     * Call a static method for a type
     *
     * @param string $type
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function &call($type, $method, $parameters = [])
    {
        $class = $this->get_class($type);

        if (in_array($class, $this->legacy)) {
            switch ($type) {
                case 'Cache':
                    // For backwards compatibility with old non-static
                    // Cache::create() methods in PHP < 8.0.
                    // No longer supported as of PHP 8.0.
                    if ($method === 'get_handler') {
                        $result = @call_user_func_array([$class, 'create'], $parameters);
                        return $result;
                    }
                    break;
            }
        }

        $result = call_user_func_array([$class, $method], $parameters);
        return $result;
    }
}

class_alias('SimplePie\Registry', 'SimplePie_Registry');