<?php

namespace App\Repositories;

use App\Models\Website\LocationSlider;
use Illuminate\Support\Facades\{Auth,Validator,Hash,URL};
use App\{User,Permission,Role,PermissionRole};
use App\Models\Salon\{BlogPost,BlogImages};
use App\Models\Website\{WebsiteContent,WebsiteImages,SliderTextBox};
use App\Models\Salons;
use DB;

class WebsiteRepository {
    
    public function setWebsiteUrl($url) {
        
        try {
            $salons = Salons::all();
            
            foreach($salons as $salon) {
                if($url === $salon->unique_url) {
                    return ['status' => 0, 'message' => trans('salon.url_taken')];
                }
            }
            
            $update_url = Salons::find(Auth::user()->salon_id);
            $valid_url = $this->setvalidURL($url);
            $update_url->unique_url = $valid_url;
            $update_url->save();
            
            return ['status' => 1, 'message' => trans('salon.url_set'), 'link' => Url::to('/').'/'.$valid_url];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }
    
    public function setvalidURL($url) {
        
        $a = ['À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö',
            'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ',
            'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ',
            'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ',
            'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ',
            'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ',
            'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů',
            'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ',
            'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', ' - ', '/', '(',
            ')', ' ', ':', '%'];

        $b = ['A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O',
            'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n',
            'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c',
            'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g',
            'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L',
            'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R',
            'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U',
            'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u',
            'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', '-',
            '', '', '', '-', '', ''];
    
        return strtolower(str_replace($a, $b, $url));
        
    }
    
    public function saveContent($data) {
        
        try {
            
            $content = WebsiteContent::where('salon_id', Auth::user()->salon_id)->first();
            $content->company_introduction = isset($data['company_introduction']) ? $data['company_introduction'] : null;
            $content->website_service_text = isset($data['website_service']) ? $data['website_service'] : null;
            $content->website_booking_text = isset($data['website_booking']) ? $data['website_booking'] : null;
            $content->website_about_text = isset($data['website_about']) ? $data['website_about'] : null;
            $content->terms_and_conditions = isset($data['terms_and_conditions']) ? $data['terms_and_conditions'] : null;
            $content->book_btn_text = isset($data['button_text']) ? $data['button_text'] : null;
            $content->book_btn_bg = isset($data['button_bg']) ? $data['button_bg'] : null;
            $content->book_btn_color = isset($data['button_text_color']) ? $data['button_text_color'] : null;
            $content->save();
            
            return ['status' => 1];
            
        } catch (Exception $exc) {
            return ['status' => 0];
        }
    }
    
    public function checkOpenStatus($salon, $hours) {
        
        $dayname = date('l');
        $current_time_obj = new \DateTime("now", new \DateTimeZone($salon->time_zone));
        $current_time = $current_time_obj->format('H:i');
        
        foreach($hours as $hour) {
            if($hour->dayname === $dayname) {
                if($current_time >= $hour->start_time && $current_time <= $hour->closing_time) {
                    $location_status = trans('salon.location_open');
                } else {
                    $location_status = trans('salon.location_closed');
                }
            }
        }
        
        return ['status' => $location_status];
        
    }
    
    public function checkUniqueUrl($unique_url) {
        
        $url = $unique_url;
        
        $check_url = BlogPost::where('unique_url', $unique_url)->first();

        if($check_url != null) {

            $extra_string = substr(md5(rand()), 0, 10);
            $new_url = $url . '-' . $extra_string;

            return $this->checkUniqueUrl($new_url);
        }
        
        return $url;
        
    }
    
    public function saveBlogPost($blog_data) {
        
        try {
            
            $detail = $blog_data['editordata'];
            libxml_use_internal_errors(true);
            $dom = new \domdocument();
            
            $dom->loadHtml($detail, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
     
            $images = $dom->getelementsbytagname('img');
            $images_arr = [];
            
            foreach($images as $k => $img){
                $data = $img->getattribute('src');
                
                $url = explode('/', $data);
                //if image is already uploaded -> get content of image from url and create new content
                if($url[0] === 'http:' || $url[0] === 'https:') {
                    
                    $data = file_get_contents($data);
                    $image_name = substr(md5(rand()), 0, 15).'.png';
                    $path = public_path() .'/images/salon-websites/blog-images/'. $image_name;
                    $url = URL::to('/').'/images/salon-websites/blog-images/'.$image_name;
                    file_put_contents($path, $data);
         
                    $img->removeattribute('src');
                    $img->setattribute('src', $url);
                    
                    $images_arr[] = [
                        'name' => $image_name,
                        'path' => $path
                    ];
                    
                } else {
                    list($type, $data) = explode(';', $data);
                    list(, $data)      = explode(',', $data);
                    
                    
                    $data = base64_decode($data);
                    
                    $image_name = substr(md5(rand()), 0, 15).'.png';
                    $path = public_path() .'/images/salon-websites/blog-images/'. $image_name;
                    $url = URL::to('/').'/images/salon-websites/blog-images/'.$image_name;
                    file_put_contents($path, $data);
         
                    $img->removeattribute('src');
                    $img->setattribute('src', $url);
                    
                    $images_arr[] = [
                        'name' => $image_name,
                        'path' => $path
                    ];
                }
                
            }

            $detail = $dom->savehtml();
            
            //if update ---> delete all old images
            if(isset($blog_data['post_id'])) {
                $blog_images_list = BlogImages::where('post_id', $blog_data['post_id'])->where('image_type', 'content_image')->get();
                foreach($blog_images_list as $blog_image_del) {
                    unlink($blog_image_del->image_location);
                    $blog_image_del->delete();
                }
            }
            
            //check unique url
            $unique_url = $this->setvalidURL($blog_data['post_title']);
            $blog_post_url = $this->checkUniqueUrl($unique_url);
            
            if(!isset($blog_data['post_id'])) {
                $blog_post = new BlogPost;
            } else {
                $blog_post = BlogPost::find($blog_data['post_id']);
            }
            
            if(isset($blog_data['featured_image'])) {
                //delete old featured image and upload new one (if user is doing update with set ftr image)
                
                $mime_type = $blog_data['featured_image']->getClientOriginalExtension();
                $ftr_image_name = substr(md5(rand()), 0, 15). '.' . $mime_type;
                $blog_data['featured_image']->move(public_path() . '/images/salon-websites/blog-images/', $ftr_image_name);
                
                if(isset($blog_data['post_id'])) {
                    $old_image = public_path().'/images/salon-websites/blog-images/'.$blog_post->featured_image;
                    unlink($old_image);
                }
                
                $blog_post->featured_image = $ftr_image_name;
            }
            
            $blog_post->salon_id = Auth::user()->salon_id;
            $blog_post->title = $blog_data['post_title'];
            $blog_post->description = $blog_data['post_description'];
            $blog_post->content = $detail;
            $blog_post->unique_url = $blog_post_url;
            $blog_post->save();
  
            foreach($images_arr as $blog_post_image) {
                $blog_image = new BlogImages;
                $blog_image->post_id = $blog_post->id;
                $blog_image->image_type = 'content_image';
                $blog_image->image_name = $blog_post_image['name'];
                $blog_image->image_location = $blog_post_image['path'];
                $blog_image->save();
            }
            
            return ['status' => 1, 'message' => trans('salon.blog_post_saved')];
            
        } catch (Exception $exc) {
            
            return ['status' => 0, 'message' => trans('salon.error_updating')];
            
        }
        
    }
    
    public function deleteBlogPost($id) {
        
        try {
            
            if($blog_post = BlogPost::find($id)) {
                
                $blog_post->delete();
                
                return ['status' => 1, 'message' => trans('salon.blog_post_deleted')];
                    
            }
            
            return ['status' => 0, 'message' => trans('salon.blog_post_not_found')];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }
    
    public function updateSocialLinks($data) {

        try {
        
            if($salon = Salons::find($data['id'])) {
                
                if($salon->website_content != null) {
                    $salon->website_content->facebook_link = isset($data['facebook_link']) ? $data['facebook_link'] : null;
                    $salon->website_content->twitter_link = isset($data['twitter_link']) ? $data['twitter_link'] : null;
                    $salon->website_content->instagram_link = isset($data['instagram_link']) ? $data['instagram_link'] : null;
                    $salon->website_content->pinterest_link = isset($data['pinterest_link']) ? $data['pinterest_link'] : null;
                    $salon->website_content->save();
                } else {
                    $content = new WebsiteContent;
                    $content->salon_id = $salon->id;
                    $content->facebook_link = isset($data['facebook_link']) ? $data['facebook_link'] : null;
                    $content->twitter_link = isset($data['twitter_link']) ? $data['twitter_link'] : null;
                    $content->instagram_link = isset($data['instagram_link']) ? $data['instagram_link'] : null;
                    $content->pinterest_link = isset($data['pinterest_link']) ? $data['pinterest_link'] : null;
                    $content->save();
                }
                
                return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
                
            }
            
            return ['status' => 0, 'message' => trans('salon.error_updating')];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function uploadSliderImages($data) {
        
        try {
        
            if($salon = Salons::find(Auth::user()->salon_id)) {
                
                if(count($salon->website_images) <= 3) {

                    $mime_type = $data['file']->getClientOriginalExtension();
                    $image_name = substr(md5(rand()), 0, 15). '.' . $mime_type;
                    $data['file']->move(public_path() . '/images/salon-websites/slider-images/', $image_name);
                    
                    //upload the new image
                    $website_image = new WebsiteImages;
                    $website_image->salon_id = $salon->id;
                    $website_image->image_name = $image_name;
                    $website_image->save();
                    
                    return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
                    
                }
                
                return ['status' => 0, 'message' => trans('salon.max_number_of_images')];
                
            }
            
            return ['status' => 0, 'message' => trans('salon.salon_not_found')];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function deleteSliderImage($id) {
        
        try {
            
            if($slider_image = WebsiteImages::find($id)) {
                
                unlink(public_path().'/images/salon-websites/slider-images/'.$slider_image->image_name);
                $slider_image->delete();
                
                return ['status' => 1, 'message' => trans('salon.deleted_successfully')];
                    
            }
            
            return ['status' => 0, 'message' => trans('salon.delete_failed')];
            
        } catch (Exception $exc) {
            
            return ['status' => 0, 'message' => $exc->getMessage()];
            
        }
    }
    
    public function saveAboutImage($data) {
        
        try {
            
            $salon = Salons::find(Auth::user()->salon_id);
            
            if($salon->website_content->about_image != null) {
                unlink(public_path().'/images/salon-websites/about-image/'.$salon->website_content->about_image);
            }
            
            $mime_type = $data['image']->getClientOriginalExtension();
            $image_name = substr(md5(rand()), 0, 15). '.' . $mime_type;
            $data['image']->move(public_path() . '/images/salon-websites/about-image/', $image_name);
            
            $salon->website_content->about_image = $image_name;
            $salon->website_content->save();
            
            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function loadBlogPosts($salon_id, $page) {
        
        if($salon = Salons::find($salon_id)) {
            
            $skip_count = $page * 2;
            $page_count = $page + 1;
            $blog_posts = BlogPost::where('salon_id', $salon->id)->skip($skip_count)->take(2)->get();
            
            return ['status' => 1, 'posts' => $blog_posts, 'page_count' => $page_count, 'salon_url' => $salon->unique_url];
            
        }
        
        return ['status' => 0];
        
    }
    
    public function updateSliderPromo($data) {
        
        try {
            
            $salon = Salons::find(Auth::user()->salon_id);

            $nr = count($salon->website_images);
            for($i = 0; $i < $nr; $i++) {
                $check = SliderTextBox::where('salon_id', $salon->id)->get();
                if($check->isNotEmpty() && isset($check[$i])) {
                    $slider = $check[$i];
                } else {
                    $slider = new SliderTextBox;
                }

                $slider->salon_id = $salon->id;
                $slider->slider_number = $i+1;
                $slider->title = isset($data['slider_content'][$i]) ? $data['slider_content'][$i]['sliderTitleValue'] : '';
                $slider->text = isset($data['slider_content'][$i]) ? $data['slider_content'][$i]['sliderTextValue'] : '';
                $slider->include_btn = isset($data['slider_content'][$i]) ? $data['slider_content'][$i]['checkboxValue'] : '';
                $slider->active = isset($data['slider_content'][$i]) ? $data['slider_content'][$i]['sliderActive'] : '';
                $slider->save();

            }

            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }

    public function getGlobalImages() {

        $images = WebsiteImages::where('salon_id', 0)->get();

        return $images;

    }

    public function uploadGlobalImages($data) {

        try {

            $mime_type = $data['file']->getClientOriginalExtension();
            $image_name = substr(md5(rand()), 0, 15). '.' . $mime_type;
            $data['file']->move(public_path() . '/images/salon-websites/slider-images/', $image_name);

            //upload the new image
            $website_image = new WebsiteImages;
            $website_image->salon_id = 0; //0 for global images
            $website_image->image_name = $image_name;
            $website_image->save();

            return ['status' => 1, 'message' => trans('salon.upload_successful')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }

    public function addSliderImage($data) {
        $slider_image = new LocationSlider;
        $slider_image->salon_id = Auth::user()->salon_id;
        $slider_image->image_id = $data['image'];
        $slider_image->save();
    }

    public function updateSliderImages($data) {

        try {

            $slider_images = LocationSlider::where('salon_id', Auth::user()->salon_id)->get();
            if($slider_images != null) {
                if($data['status'] == 0) {
                    foreach($slider_images as $image) {
                        if($image->image_id == $data['image']) {
                            $image->delete();
                        }
                    }
                } else {
                    if(count($slider_images) >= 3) {
                        return ['status' => 0, 'message' => trans('salon.max_number_of_images')];
                    } else {
                        $this->addSliderImage($data);
                    }
                }
            } else {
                $this->addSliderImage($data);
            }

            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }

    public function getSliderImages() {
        $slider_images = LocationSlider::where('salon_id', Auth::user()->salon_id)->get();
        $slider_arr = [];
        if($slider_images->isNotEmpty()) {
            foreach($slider_images as $image) {
                $slider_arr[$image->image_id][] = [
                    'id' => $image->id
                ];
            }
        }
        return $slider_arr;
    }
}