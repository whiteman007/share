<?php
if (!defined('SpiderShare')) die();
/** Simple and smart SQL query builder for PDO.
 *
 * @category    Library
 * @version        0.9.2
 * @author    guncebektas <info@guncebektas.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link        http://guncebektas.com
 * @link        http://github.com/guncebektas/lenkorm
 *
 * ->write        : will show you the query string
 * ->run        : will run the query
 * ->result        : will return the result of selected result (only one row)
 * ->results    : will return the results of query (multi row)
 *
 * otherwise you will only create query string!
 *
 * insert_id, find, columns, insert methods will be exacuted directly
 *
 * Examples:
 *
 * 1. THIS WILL SELECT ALL ROWS IN SLIDES TABLE
 * select('slides')->results();
 *
 *
 * 2. INSERT ARRAY INTO SLIDES TABLE
 *
 * insert('slides')->values(array('slide_img'=>$_POST['slide_img'],
 * 'slide_title'=>$_POST['slide_title'],
 * 'slide_text'=>$_POST['slide_text'],
 * 'slide_href'=>$_POST['slide_href']));
 *
 *
 * 3. UPDATE SLIDES TABLE
 *
 * update('slides')->values(array('slide_img'=>$_POST['slide_img'],
 * 'slide_title'=>$_POST['slide_title'],
 * 'slide_text'=>$_POST['slide_text'],
 * 'slide_href'=>$_POST['slide_href']))->where('slide_id = 1');
 *
 * PS 1: you can put array into values like values($_POST) if columns match with the index of array
 *
 * PS 2: use security function in where clause to block SQL injection like
 * ->where('slide_id = '.security($_GET['slide_id']));
 */

/**
 * Settings to connect database

$db = array(
 * 'server' => 'localhost',
 * 'db_name' => '',
 * 'type' => 'mysql',
 * 'user' => '',
 * 'pass' => '',
 * 'charset' => 'charset=utf8'
 * );
 *
 * $pdo = new _pdo($db);
 *
 */
class MDB extends PDO
{
    public $query;
    public $memcache = false;
    public $cache_time = 600;
    private $type;
    private $values;

    public function __construct($db)
    {

        //if (self::getSerial() !== "FF8MC2S") {
            //die('license error please contact : 07707751395 - info@sp4dev.com');
       // }

        try {

            /* Connect to database */
            parent::__construct($db['type'] . ':host=' . $db['server'] . ';dbname=' . $db['db_name'] . ';' . $db['charset'], $db['user'], $db['pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            /* Extend PDO statement class*/
            $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('_pdo_statement'));
            /* Disable emulated prepared statements */
            $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            /* Set default fetch mode*/
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            /* Include UPDATED QUERIES in to rowcount() function */
            //$this->setAttribute(PDO::MYSQL_ATTR_FOUND_ROWS, true);
            /* Error mode is exception */
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        } catch (PDOException $e) {
            die('<p><strong>Error:</strong> ' . $e->getMessage() . '</p>
                 <p><strong>File:</strong> ' . $e->getFile() . '</br>
                 <p><strong>Line:</strong> ' . $e->getLine() . '</p>');
        }

    }


    private function getSerial()
    {
        //www-data ALL=(root) NOPASSWD : /usr/bin/sudo
        //www-data ALL=(root) NOPASSWD : /bin/cat
        return exec("sudo cat /sys/devices/virtual/dmi/id/product_serial");
    }


    public function insert_id()
    {
        return $this->lastInsertId();
    }

    public function find($table, $id)
    {
        $columns = $this->column(self::security($table));

        return $this->select(self::security($table))->where($columns['Field'] . ' = ' . self::security($id))->limit(1)->result();
    }

    public function column($table)
    {
        $query = $this->query('SHOW COLUMNS FROM ' . self::security($table));

        return $query->fetch();
    }

    function security($input)
    {
        // Clear not allowed chars
        $input = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $input);

        // Search for these
        $search = 'abcdefghijklmnopqrstuvwxyz';
        $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $search .= '1234567890!@#$%^&*()';
        $search .= '~`";:?+/={}[]-_|\'\\';

        // Clear not allowed chars again
        for ($i = 0; $i < strlen($search); $i++) {
            $input = preg_replace('/(&#[x|X]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $input);
            $input = preg_replace('/(&#0{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $input);
        }

        // Remove java, flash etc..
        $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
        $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

        // Merge arrays
        $ra = array_merge($ra1, $ra2);

        // Remove possible threats which are defined above
        $find = true;
        while ($find == true) {
            $first = $input;
            for ($i = 0; $i < sizeof($ra); $i++) {
                $action = '/';
                for ($j = 0; $j < strlen($ra[$i]); $j++) {
                    if ($j > 0) {
                        $action .= '(';
                        $action .= '(&#[x|X]0{0,8}([9][a][b]);?)?';
                        $action .= '|(&#0{0,8}([9][10][13]);?)?';
                        $action .= ')?';
                    }
                    $action .= $ra[$i][$j];
                }

                $action .= '/i';
                $change = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2);
                $input = preg_replace($action, $change, $input);

                if ($first == $input) {
                    $find = false;
                }
            }
        }

        // Allowed tags
        $result = strip_tags($input, '<p><strong><em><b><i><ul><li><pre><hr><blockquote><span>');

        // Change special chars to their html version
        $result = htmlspecialchars($result);

        // \n to <br>
        $result = str_replace("\n", '<br />', $result);

        // Add slash
        $result = addslashes($result);

        return $result;
    }

    final public function result($key = '')
    {
        if (!$this->memcache) {
            $query = $this->run(true);

            if (!$key) {
                return $query->fetch();
            } else {
                $result = $query->fetch();

                return $result[$key];
            }
        }

        $memcache = new Memcache();
        $memcache->connect('127.0.0.1', 11211) or die('MemCached connection error!');

        $data = $memcache->get('query-' . md5($this->query));

        if (!isset($data) || $data === false) {
            $query = $this->run(true);

            if (!$key) {
                return $query->fetch();
            } else {
                $result = $query->fetch();

                return $result[$key];
            }

            $memcache->set('query-' . md5($this->query), $result, MEMCACHE_COMPRESSED, $this->cache_time);

            return $result;
        } else {
            return $data;
        }
    }

    final public function run($return = false)
    {
        if ($return) {
            return $this->query($this->query);
        }

        $this->query($this->query);
    }

    public function limit($limit = 3000)
    {
        $this->query .= ' LIMIT ' . self::security($limit) . ' ';

        return $this;
    }

    public function where($condition)
    {
        $this->query .= ' WHERE ' . $condition;

        if ($this->type == 'update') {
            $query = $this->prepare($this->query);

            // If the values are formed as an array than encode it
            foreach ($this->values AS $value) {
                if (is_array($value))
                    $value = json_encode($value);

                $res[] = $value;
            }

            $query->execute($res);

            return $this;
        } else {
            return $this;
        }
    }

    public function select($table, $select = '')
    {
        $this->query = 'SELECT ' . ($select != '' ? $select : "*") . ' FROM ' . self::security($table) . ' ';

        return $this;
    }

    public function left($condition)
    {
        $this->query .= 'LEFT JOIN ' . self::security($condition) . ' ';

        return $this;
    }

    public function using($column)
    {
        $this->query .= ' USING (' . self::security($column) . ')';

        return $this;
    }

    public function insert($table)
    {
        $this->type = 'insert';

        $this->query = 'INSERT INTO ' . self::security($table) . @' ';

        return $this;
    }

    public function replace($table)
    {
        $this->type = 'insert';

        $this->query = 'REPLACE INTO ' . self::security($table) . @' ';

        return $this;
    }

    public function update($table)
    {
        $this->type = 'update';

        $this->query = 'UPDATE ' . self::security($table) . ' SET ';

        return $this;
    }

    public function delete($table, $id = '')
    {
        if (empty($id)) {
            $this->query = 'DELETE FROM ' . self::security($table) . ' ';

            return $this;
        } else {
            // Key is not empty, so delete by first column match
            $columns = $this->column($table);
            $this->delete($table)->where('' . self::security($columns['Field']) . ' = "' . self::security($id) . '"')->limit(1)->run();
        }
    }

    public function alter($table)
    {
        $this->query = 'ALTER TABLE ' . self::security($table) . @' ';

        return $this;
    }

    public function rename_to($new_name)
    {
        $this->query .= 'RENAME ' . self::security($new_name);

        $this->query($this->query);
    }

    public function add_column($column, $datatype)
    {
        $this->query .= 'MODIFY COLUMN ' . self::security($column) . ' ' . self::security($datatype);

        $this->query($this->query);
    }

    public function drop_column($column)
    {
        $this->query .= 'DROP COLUMN ' . self::security($column);

        $this->query($this->query);
    }

    public function add_index($name, $column)
    {
        $this->query .= 'ADD INDEX ' . self::security($name) . ' (' . self::security($column) . ')';

        $this->query($this->query);
    }

    public function increase($column, $value = 1)
    {
        $column = self::security($column);
        $this->query .= $column . ' = ' . $column . ' + ' . (int)$value . ' ';

        return $this;
    }

    public function decrease($column, $value = 1)
    {
        $column = self::security($column);
        $this->query .= $column . ' = ' . $column . ' - ' . (int)$value . ' ';

        return $this;
    }

    public function values($values)
    {
        $this->values = $values;

        $keys = array_keys($values);
        $vals = array_values($values);

        /* INSERT INTO books (title,author) VALUES (:title,:author); */
        if ($this->type == 'insert') {
            $row = '(';
            for ($i = 0; $i < count($values); $i++) {
                $row .= $keys[$i];

                if ($i != count($values) - 1) {
                    $row .= ', ';
                } else {
                    $row .= ') VALUES (';
                }
            }
            for ($i = 0; $i < count($values); $i++) {
                $row .= ':' . $keys[$i];

                if ($i != count($values) - 1) {
                    $row .= ', ';
                } else {
                    $row .= ')';
                }
            }
            $this->query .= self::security($row);
            $query = $this->prepare($this->query);

            // If the values are formed as an array than encode it
            foreach ($values AS $value) {
                if (is_array($value))
                    $value = json_encode($value);

                $res[] = $value;
            }
            /*
            echo $this->query;
            // Bind params
            foreach ($keys AS $key){
                $this->bindParam(':'.$key, $key);
            }
            */
            $query->execute($res);
            return $this;
        } /* UPDATE books SET title=:title, author=:author */
        elseif ($this->type == 'update') {
            for ($i = 0; $i < count($values); $i++) {
                $this->query .= self::security($keys[$i]) . ' = :' . self::security($keys[$i]) . ' ';
                if ($i != count($values) - 1) {
                    $this->query .= ', ';
                }
            }

            return $this;
        }
    }

    public function which($condition)
    {
        $this->query = str_replace('*', self::security($condition), $this->query);

        return $this;
    }

    public function group($condition)
    {
        $this->query .= ' GROUP BY ' . self::security($condition);;

        return $this;
    }

    public function have($condition)
    {
        $this->query .= ' HAVING ' . $condition;

        return $this;
    }

    public function order($condition)
    {
        $this->query .= ' ORDER BY ' . self::security($condition);

        return $this;
    }

    public function offset($offset = 3000)
    {
        $this->query .= ' OFFSET ' . self::security($offset) . ' ';

        return $this;
    }

    final public function write()
    {
        echo $this->query;
    }

    final public function multi_result($key = array())
    {
        if (!$this->memcache) {
            $query = $this->run(true);

            if (!$key) {
                return $query->fetch();
            } else {
                $result = $query->fetch();

                if ($result) {
                    $data = array();
                    foreach ($key as $value) {
                        array_push($data, $result[$value]);
                    }
                }

                return $data;
            }
        }

        $memcache = new Memcache();
        $memcache->connect('127.0.0.1', 11211) or die('MemCached connection error!');

        $data = $memcache->get('query-' . md5($this->query));

        if (!isset($data) || $data === false) {
            $query = $this->run(true);

            if (!$key) {
                return $query->fetch();
            } else {
                $result = $query->fetch();

                return $result[$key];
            }

            $memcache->set('query-' . md5($this->query), $result, MEMCACHE_COMPRESSED, $this->cache_time);

            return $result;
        } else {
            return $data;
        }
    }

    final public function results_pairs($key, $values = '')
    {
        $results = $this->results();

        foreach ($results as $result) {
            foreach ($values as $value) {
                $res[$result[$key]][$value] = $result[$value];
            }
        }

        return $res;
    }

    final public function results($cache = true)
    {
        if (!$this->memcache || $cache == false) {
            $query = $this->run(true);
            $results = $query->fetch_array();

            return $results;
        }

        $memcache = new Memcache();
        $memcache->connect('127.0.0.1', 11211) or die('MemCached connection error!');

        $data = $memcache->get('query-' . md5($this->query));
        if (!isset($data) || $data === false) {
            $query = $this->run(true);
            $results = $query->fetch_array();

            $memcache->set('query-' . md5($this->query), $results, MEMCACHE_COMPRESSED, $this->cache_time);

            return $results;
        } else {
            return $data;
        }
    }

    final public function numRows()
    {
        $query = $this->run(true);
        return $query->num_rows();

        $results = $query->fetch_array();
        return count($results);
    }

    final public function affectedRows()
    {
        $query = $this->run(true);
        return $query->affected_rows();

        $results = $query->fetch_array();
        return count($results);

    }

    function clean($input)
    {
        $input = str_replace("\'", "'", $input);
        $input = str_replace('\\\\', '\\', $input);
        $input = str_replace('<br />', "\n", $input);
        $input = str_replace('&amp;', '&', $input);
        $input = str_replace('&quot;', '"', $input);
        $input = str_replace('<', '&lt;', $input);
        $input = str_replace('>', '&gt;', $input);

        return $input;
    }
}

/* Extend PDOStatement for some methods */

class _pdo_statement extends PDOStatement
{
    /* Set the rule of fetchAll. Values will be returned as PDO::FETCH_ASSOC in fetch_array and fetch_assoc functions */
    public function fetch_array()
    {
        return $this->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetch_assoc()
    {
        return $this->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Return number of rows */
    public function num_rows()
    {
        return $this->rowCount();
    }

    /* Return affected wors */
    public function affected_rows()
    {
        return $this->rowCount();
    }
}
