<?php

namespace App\Http\Controllers;

use App\Models\Font;
use Illuminate\Support\Facades\Cache;

class StyleController extends Controller
{
    public function font()
    {
        return Font::css();
    }
    public function colorRange($name, $color)
    {
        $css = '';
        $css .= "--color-$name: var(--color-$color-600);";
        collect(range(1, 9))->each(function ($i) use ($name, $color, &$css) {
            $n = $i * 100;
            $css .= "--color-$name-$n: var(--color-$color-$n);";
        });
        $css .= "--color-$name-950: var(--color-$color-950);";
        return $css;
    }
    public function colors()
    {
        $primary = get_option('design.color_primary');
        $secondary = get_option('design.color_secondary');
        $accent = get_option('design.color_accent');
        $css = '';
        if (!empty($primary) || !empty($secondary)) {
            $css .= ':root,:host {';
            if (!empty($primary)) {
                $css .= $this->colorRange('primary', $primary);
            }
            if (!empty($secondary)) {
                $css .= $this->colorRange('secondary', $secondary);
            }
            if (!empty($accent)) {
                $css .= $this->colorRange('accent', $accent);
            }
            $css .= '}';
        }
        return $css;
    }

    public function body()
    {
        $data = '';
        $fontFamily = get_option('typography.font_family', '');
        if (!empty($fontFamily)) {
            $data .= 'font-family: ' . $fontFamily . ';';
        }
        $fontSmoothing = get_option('typography.font_smoothing', '');
        if (!empty($fontSmoothing)) {
            $data .= 'font-smoothing: ' . $fontSmoothing . ';';
        }
        $fontSize = get_option('typography.font_size', '');
        if (!empty($fontSize)) {
            $data .= 'font-size: ' . $fontSize . ';';
        }
        $fontWeight = get_option('typography.font_weight', '');
        if (!empty($fontWeight)) {
            $data .= 'font-weight: ' . $fontWeight . ';';
        }
        $fontStyle = get_option('typography.font_style', '');
        if (!empty($fontStyle)) {
            $data .= 'font-style: ' . $fontStyle . ';';
        }
        $fontDisplay = get_option('typography.font_display', '');
        if (!empty($fontDisplay)) {
            $data .= 'font-display: ' . $fontDisplay . ';';
        }
        $css = '';
        if (!empty(trim($data))) {
            $css .= 'body {';
            $css .= $data;
            $css .= '}';
        }
        return $css;
    }
    public function index()
    {
        $options = [
            'font_family'      => get_option('typography.font_family', ''),
            'font_smoothing'   => get_option('typography.font_smoothing', ''),
            'font_size'        => get_option('typography.font_size', ''),
            'font_weight'      => get_option('typography.font_weight', ''),
            'font_style'       => get_option('typography.font_style', ''),
            'font_display'     => get_option('typography.font_display', ''),
            'color_primary'   => get_option('design.color_primary', ''),
            'color_secondary' => get_option('design.color_secondary', ''),
            'color_accent'    => get_option('design.color_accent', ''),
            'fonts' => Font::enabled()->pluck('id')->toArray(),
        ];
        $cacheKey = 'style_css_' . md5(json_encode($options));
        $css = Cache::rememberForever($cacheKey, function () {
            $css = $this->font();
            $css .= $this->colors();
            // $css .= $this->body();
            return $css;
        });

        return response()->make($css, 200, ['Content-Type' => 'text/css']);
    }
}
