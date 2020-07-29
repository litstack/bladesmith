<?php

namespace Fjord\Ui\Components;

use Fjord\Support\Facades\Form;
use Illuminate\View\Component;

class MetaTagsComponent extends Component
{
    /**
     * The meta title.
     *
     * @var string
     */
    public $metaTitle;

    /**
     * The meta description.
     *
     * @var string
     */
    public $metaDescription;

    /**
     * The meta keywords.
     *
     * @var string
     */
    public $metaKeywords;

    /**
     * Create a new component instance.
     *
     * @param  string|null $metaTitle
     * @param  string|null $metaDescription
     * @param  string|null $metaKeywords
     * @return void
     */
    public function __construct($metaTitle = null, $metaDescription = null, $metaKeywords = null)
    {
        $settings = null;

        if (! $metaTitle || ! $metaDescription || ! $metaKeywords) {
            $settings = Form::load('collections', 'settings');
        }

        $this->metaTitle = $metaTitle ?: $settings->meta_title ?? '';
        $this->metaDescription = $metaDescription ?: $settings->meta_description ?? '';
        $this->metaKeywords = $metaKeywords ?: $settings->meta_keywords ?? '';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('fjord-ui::meta-tags');
    }
}
