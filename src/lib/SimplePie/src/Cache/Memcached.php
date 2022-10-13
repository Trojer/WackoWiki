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

namespace SimplePie\Cache;

use Memcached as NativeMemcached;

/**
 * Caches data to memcached
 *
 * Registered for URLs with the "memcached" protocol
 *
 * For example, `memcached://localhost:11211/?timeout=3600&prefix=sp_` will
 * connect to memcached on `localhost` on port 11211. All tables will be
 * prefixed with `sp_` and data will expire after 3600 seconds
 *
 * @package    SimplePie
 * @subpackage Caching
 * @author     Paul L. McNeely
 * @uses       Memcached
 */
class Memcached implements Base
{
    /**
     * NativeMemcached instance
     * @var NativeMemcached
     */
    protected $cache;

    /**
     * Options
     * @var array
     */
    protected $options;

    /**
     * Cache name
     * @var string
     */
    protected $name;

    /**
     * Create a new cache object
     * @param string $location Location string (from SimplePie::$cache_location)
     * @param string $name     Unique ID for the cache
     * @param string $type     Either TYPE_FEED for SimplePie data, or TYPE_IMAGE for image data
     */
    public function __construct($location, $name, $type)
    {
        $this->options = [
            'host'   => '127.0.0.1',
            'port'   => 11211,
            'extras' => [
                'timeout' => 3600, // one hour
                'prefix'  => 'simplepie_',
            ],
        ];
        $this->options = \SimplePie\Misc::array_merge_recursive($this->options, \SimplePie\Cache::parse_URL($location));

        $this->name = $this->options['extras']['prefix'] . md5("$name:$type");

        $this->cache = new NativeMemcached();
        $this->cache->addServer($this->options['host'], (int)$this->options['port']);
    }

    /**
     * Save data to the cache
     * @param array|SimplePie $data Data to store in the cache. If passed a SimplePie object, only cache the $data property
     * @return bool Successfulness
     */
    public function save($data)
    {
        if ($data instanceof \SimplePie\SimplePie) {
            $data = $data->data;
        }

        return $this->setData(serialize($data));
    }

    /**
     * Retrieve the data saved to the cache
     * @return array Data for SimplePie::$data
     */
    public function load()
    {
        $data = $this->cache->get($this->name);

        if ($data !== false) {
            return unserialize($data);
        }
        return false;
    }

    /**
     * Retrieve the last modified time for the cache
     * @return int Timestamp
     */
    public function mtime()
    {
        $data = $this->cache->get($this->name . '_mtime');
        return (int) $data;
    }

    /**
     * Set the last modified time to the current time
     * @return bool Success status
     */
    public function touch()
    {
        $data = $this->cache->get($this->name);
        return $this->setData($data);
    }

    /**
     * Remove the cache
     * @return bool Success status
     */
    public function unlink()
    {
        return $this->cache->delete($this->name, 0);
    }

    /**
     * Set the last modified time and data to NativeMemcached
     * @return bool Success status
     */
    private function setData($data)
    {
        if ($data !== false) {
            $this->cache->set($this->name . '_mtime', time(), (int)$this->options['extras']['timeout']);
            return $this->cache->set($this->name, $data, (int)$this->options['extras']['timeout']);
        }

        return false;
    }
}

class_alias('SimplePie\Cache\Memcached', 'SimplePie_Cache_Memcached');