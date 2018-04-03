<?php
namespace App\Http\Picl0u;

class Tree
{
    /**
     * @var array
     */
    public $data;
    /**
     * @var string
     */
    private $class;
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $attr;

    public function __construct(string $attr = "ul", string $class = "list-tree", string $id = "list-tree")
    {
        $this->class = $class;
        $this->id = $id;
        $this->attr = $attr;
    }

    /**
     * @param int $id
     * @param int $parent
     * @param string $label
     * @param string $liAttr
     * @param string|null $link
     * @param string $ulClass
     */
    public function addRow(
        int $id,
        int $parent,
        string $label,
        string $liAttr = "",
        string $link = null,
        string $ulClass = ""
    )
    {
        $this->data[$parent][] = [
            'id' => $id,
            'liAttr' => $liAttr,
            'label' => $label,
            'link' => $link,
            'ulClass' => $ulClass,
        ];
    }

    /**
     * @param null $attr
     * @return bool|string
     */
    public function generateList($attr = null)
    {
       return $this->ul(0, $attr, "first-ul", $this->id);
    }

    /**
     * @param int $parent
     * @param string $attr
     * @param string $class
     * @param bool $id
     * @return bool|string
     */
    private function ul(int $parent = 0, $attr = "", string $class = "", $id = false)
    {
        static $i = 1;
        if (isset($this->data[$parent])) {
            if($attr) {
                $this->attr = ' ' . $attr;
            }
            $html = '<'.$this->attr.' class="'.$class.'">';
            $i++;
            foreach ($this->data[$parent] as $row) {
                $child = $this->ul($row['id'], $row['liAttr'], $row['ulClass']);
                $html .= '<li '.$row['liAttr'].'>';
                    if (!is_null($row['link'])){
                       $html .= '<a href="' . $row['link']. '">';
                    }
                        $html .= $row['label'];
                    if (!is_null($row['link'])){
                        $html .= '</a>';
                    }
                    if($child){
                        $html .= $child;
                    }
                $html .= '</li>';
            }
            $html .= '</'.$this->attr.'>';
            return $html;
        } else {
            return false;
        }
    }

}
