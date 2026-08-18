<?php
use App\Models\Supporter;
if (!function_exists('getOption')) {
    /**
     * Get option of the site
     *
     * @param string $key
     * @param mix $default
     *
     * @return mix
     */
    function getOption($key, $default = null)
    {
        return app('OptionData')->get($key, $default);
    }
}

if (!function_exists('deferOption')) {
    /**
     * Get custom value and fallback to the option data when the custom value not exist
     *
     * @param mix $firstValue
     * @param string $key
     *
     * @return mix
     */
    function deferOption($firstValue, $key, $default = null)
    {
        return !empty($firstValue)
        ? $firstValue
        : app('OptionData')->get($key, $default);
    }
}

if (!function_exists('setOption')) {
    /**
     * Set option of the site
     *
     * @param string $key
     * @param mix $value
     *
     * @return mix
     */
    function setOption($key, $value)
    {
        return app('OptionData')->set($key, $value);
    }
}

if (!function_exists('segment')) {
    /**
     * Get the segment url
     *
     * @param string $segment
     *
     * @return string
     */
    function segment($segment)
    {
        return Request::segment($segment);
    }
}

if (!function_exists('imgUser')) {
    /**
     * Get user image url
     *
     * @param  string $filename
     * @param  string $type
     * @param  null|string(m,f) $gender
     * @return string
     */
    function imgUser($filename = '', $type = 'medium', $gender = null)
    {
        $file_path = "uploads/users/{$type}/{$filename}";

        // get default image when user image doesn't exist
        if (!is_file(public_path($file_path))) {
            if ($gender) {
                $file_default = ($gender == 'm') ? 'default-m.jpg' : 'default-f.jpg';
            } else {
                $file_default = 'default-f.jpg';
            }
            return url(str_replace(basename($file_path), $file_default, $file_path));
        }

        return url($file_path);
    }
}

if (!function_exists('media')) {
    /**
     * Get media url
     *
     * @param  string $filename
     * @param  string $type
     * @return string
     */
    function media($filename = '', $type = 'small')
    {
        if (!$filename) {
            return null;
        }
        // return when given filename is url
        if (str_contains($filename, 'http')) {
            return $filename;
        }

        if (!$filename) {
            return url("media/images/{$type}/default.jpg");
        }

        $file_path = "media/images/{$type}/{$filename}";

        // get default image when media doesn't exist
        if (!is_file(public_path($file_path))) {
            return url(str_replace($filename, 'default.jpg', $file_path));
        }

        return url($file_path);
    }
}

if (!function_exists('formatTime')) {
    /**
     * Convert format time
     *
     * @param  string $date
     * @param  string $format. e.g : [human, l, j F Y, your own]
     * @return \Carbon\Carbon
     */
    function formatTime($date, $format = 'j M, Y')
    {
        if ($format == 'human') {
            return \Carbon\Carbon::parse($date)->diffForHumans();
        }
        return \Carbon\Carbon::parse($date)->format($format);
    }
}

if (!function_exists('priceFormat')) {
    /**
     * Convert price format
     *
     * @param  string $date
     */
    function priceFormat($price, $currency = 'Rp ')
    {
        if ($price) {
            return $currency . number_format($price, 0, ',', '.');
        }

        return $currency . 0;
    }
}

if (!function_exists('has_donate')) {
    /**
    * Cek apakah user telah melakukan sebuah donasi terhadap project yang sedang dibuka
    *
    * @param project_id, supporter_id
    */

    function has_donate($project_id, $user_id)
    {
        return Supporter::where('project_id',$project_id)
                        ->where('user_id',$user_id)
                        ->count();
    }
}

if (!function_exists('redirectMessage')) {
    /**
     * Redirect with flash message
     *
     * @param  string $url
     * @param  string $title
     * @param  string $content
     * @param  string $type
     * @return Redirect
     */
    function redirectMessage($url, $title, $content = null, $type = '')
    {
        if ($url == 'back') {
            $redirect = redirect()->back();
        } else {
            $redirect = redirect()->to($url);
        }

        return $redirect->with('message', [
            'title' => $title,
            'content' => $content,
            'type' => $type,
        ]);
    }
}

if (!function_exists('isExpiredDate')) {
    /**
     * check is project has ended 
     *
     * @param  string $date
     */
    function isExpiredDate($dateEnd)
    {
        $temp = intval((strtotime($dateEnd) - Time()) / 86400);
        if ($temp <= 0) {
            return true; //expired
        } else {
            return false; //
        }
    }
}

if (!function_exists('getMessageSupport')) {
    /**
     * @param status
     * @return pesan 
     * call getMessageSupport($support['status'])
     */
    function getMessageSupport($status)
    {
        if ($status == "pending")
            return "Pending (kami akan segera meriview dukungan anda)";
        if ($status == "accept")
            return "Accept (pembayaran anda telah kami terima)";
    }
}

if (!function_exists('statusSupport')) {
    /**
     * @param status
     * @return true / false
     * call getMessageSupport($support['status'])
     */
    function statusSupport($status)
    {
        if ($status == "pending")
            return false;
        if ($status == "accept")
            return true;
    }
}

if (!function_exists('checkCategory')) {
    /**
     * @param status
     * @return message
     * 
     */
    function checkCategory($category)
    {
        if ($category == "" || $category == null)
            return "undifined";
        else 
            return $category;
    }
}

if (!function_exists('checkKota')) {
    /**
     * @param status
     * @return message
     * 
     */
    function checkKota($kota)
    {
        if ($kota == "" || $kota == null)
            return "undifined";
        else 
            return $kota;
    }
}

if (!function_exists('dropdown')) {
    /**
     * @param dropdown(name, data, default option, extra(class="form") );
     * @return select view
     * call dropdown('size', array('L' => 'Large', 'S' => 'Small'), 'S');
     */
    function dropdown( $name, $options=[], $selected, $extra = '') {
        $html = '';
        foreach($options as $value => $text) {
            $set_selected = '';
            if($value == $selected) {
                $set_selected = 'selected';
            }
            $html .= '<option value="'.$value.'" '.$set_selected.'>'.$text.'</option>';
        }
        return '<select '.$extra.'>'.$html.'</select>';
    }
}

if (!function_exists('customDropdown')) {
    /**
     * @param dropdown(name, data from db, default option, extra(class="form") );
     * @return select view
     * call dropdown('size', $provinsi, 'S');
     */
    function customDropdown( $name, $firstOption, $options=[], $id_value, $id_name, $selected, $extra = '') {
        $html = '';
        if($firstOption!="") $html .= '<option value="">'.$firstOption.'</option>';
        foreach($options as $value => $text) {
            $set_selected = '';
            if($text->$id_value == $selected) {
                $set_selected = 'selected';
            }
            $html .= '<option value="'.$text->$id_value.'" '.$set_selected.'>'.$text->$id_name.'</option>';
        }
        return '<select '.$extra.'>'.$html.'</select>';
    }
}

if (!function_exists('custom_generate_link')) {
    /**
     * output
     * 
     */
    function custom_generate_link($route, $param_name, $param_value, $konten)
    {
        $temp = Input::all();
        $temp[$param_name] = $param_value;
        $html = link_to_route($route, $konten, $temp, $attributes = array());
        return $html;
        /*
        -- output --
         <a href="http://localhost:8000/projects?keyword=&kategory=&lokasi=&page=2&name=myname">Terdanai</a>  
        */
    }
}

if (!function_exists('comparisson_to_active')) {
    /**
     * 
     */
    function comparisson_to_active($input, $compare)
    {
        if ($input == $compare)
            return 'class="active"';
        else 
            return '';
    }
}

if (!function_exists('isPermitted')) {
    function isPermitted($routeName) {
        $isMoreThanOne = is_array($routeName);

        $isPermitted = function($routeName) {
            $user = auth()->user();
            $privileges = $user->privileges;
        
            $isPermitted = !!$privileges->first(function($key, $privilege) use ($routeName) {
                $isValidRoute = $privilege->menuAdmin->link === $routeName;
                $isAccessible = !!$privilege->can_access;
        
                return $isValidRoute && $isAccessible;
            });
        
            return $isPermitted;
        };

        if ($isMoreThanOne) {
            return collect($routeName)->map('isPermitted')->contains(TRUE);
        }

        return $isPermitted($routeName);
    }
}