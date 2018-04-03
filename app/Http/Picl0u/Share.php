<?php
namespace  App\Http\Picl0u;

class Share
{

    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $text;
    /**
     * @var null
     */
    private $media;
    /**
     * @var array
     */
    private $websites;

    private $view;

    /**
     * Share constructor.
     * @param string $url
     * @param string $text
     * @param null $media
     * @param array $websites
     */
    public function __construct(string $url, string $text, $media = null, array $websites = [])
    {
        $this->url = $url;
        $this->text = $text;
        $this->media = $media;
        $this->websites = $websites;
        $this->view = config('ikCommerce.shareView');
    }

    /**
     * @param \Illuminate\Config\Repository|mixed $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }

    public function renderArray(): array
    {
        $shares = [];
        if (!empty($websites)) {
            foreach($websites as $website) {
                $shares[$website] = $this->$website();
            }
        } else {
            foreach(config('ikCommerce.shareWebsites') as $website) {
                $shares[$website] = $this->$website();
            }
        }
        return $shares;
    }

    public function render()
    {
        $websites = $this->renderArray();
        return view($this->view, compact('websites'));
    }

    public function facebook($text = null): array
    {
        return [
            'name' => (!is_null($text))?$text:__('generals.shareFacebook'),
            'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . $this->url . '&title=' . $this->text
        ];
    }

    public function twitter($text = null): array
    {
        return [
            'name' => (!is_null($text))?$text:__('generals.shareTwitter'),
            'url' => 'https://twitter.com/intent/tweet?url=' . $this->url . '&text=' . $this->text
        ];
    }

    public function pinterest($text = null): array
    {
        $url =  'http://pinterest.com/pin/create/button/?url=' . $this->url . '&description=' . $this->text;
        if(!is_null($this->media)) {
            $url .= '&media=' . $this->media;
        }

        return [
            'name' => (!is_null($text))?$text:__('generals.sharePinterest'),
            'url' => $url
        ];
    }

    public function gplus($text = null): array
    {
        return [
            'name' => (!is_null($text))?$text:__('generals.shareGplus'),
            'url' => 'https://plus.google.com/share?url=' . $this->url
        ];
    }


}
