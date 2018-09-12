<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Validator,Auth};

use App\Repositories\{SalonRepository,WebsiteRepository,ClientRepository};
use App\Models\{Salons,Location,LocalHours};
use App\Models\Booking\{BookingPolicy,CalendarOptions,CalendarSettings};
use App\Models\Salon\{LocationPhotos,BlogPost,Category,LoyaltyPrograms};
use App\Models\Website\{WebsiteContent,WebsiteImages};
use App\User;
use App;

class WebsiteController extends Controller {
    
    public function __construct() {
        $this->salon_repo = new SalonRepository;
        $this->client_repo = new ClientRepository;
        $this->website_repo = new WebsiteRepository;
    }
    
    public function websiteSettings() {

        $salon = Salons::find(Auth::user()->salon_id);

        $global_images = $this->website_repo->getGlobalImages();

        $location_slider = $this->website_repo->getSliderImages();

        return view('website.websiteSettings', ['salon' => $salon, 'global_images' => $global_images, 'slider_arr' => $location_slider]);
    }
    
    public function getSalonWebsite($unique_url) {
        
        $salon = Salons::where('unique_url', $unique_url)->first();
        
        if($salon != null) {
        
            App::setLocale($salon->country);
            
            $website_content = WebsiteContent::where('salon_id', $salon->id)->first();
            
            $location_markers = [];
            
            $blog_posts = BlogPost::where('salon_id', $salon->id)->take(4)->orderBy('id', 'DESC')->get();

            $location = Location::where('salon_id', $salon->id)->first();

            $location_hours = LocalHours::where('location_id', $location->id)->get();

            $cat_list = $this->salon_repo->getActiveCategories($location);

            foreach($salon->locations as $location) {
                $location_markers[] = [
                    'location_name' => $location->location_name,
                    'address' => $location->address,
                    'city' => $location->city,
                    'phone' => $location->business_phone,
                    'email' => $location->email_address,
                    'lat' => $location->lat,
                    'lng' => $location->lng,
                    'unique_url' => $location->unique_url
                ];
            }
    
            return view('website.websiteIndex', ['salon' => $salon, 'location' => $location, 'open_hours' => $location_hours, 'categories' => $cat_list, 'website_content' => $website_content, 'location_markers' => $location_markers, 'latest_news' => $blog_posts]);
            
        }
        
        return view('website.404', ['message' => trans('salon.website_not_found')]);
    }
    
    public function setWebsiteUrl(Request $request) {
        
        $url = $this->website_repo->setWebsiteUrl($request->url);
        
        if($url['status'] === 1) {
            return ['status' => 1, 'message' => $url['message'], 'url' => $url['link']];
        }
        
        return ['status' => 0, 'message' => $url['message']];
        
    }
    
    public function saveWebsiteContent(Request $request) {

        $content = $this->website_repo->saveContent($request->all());

        if($content['status'] === 1) {
            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
        }
        
        return ['status' => 0, 'message' => trans('salon.error_updating')];
        
    }
    
    public function getLocationWebsite($salon, $location) {

        $salon = Salons::where('unique_url', $salon)->first();
        
        $location = Location::where('salon_id', $salon->id)->where('unique_url', $location)->first();

        if($salon != null && $location != null) {
            
            App::setLocale($salon->country);
            
            $location_hours = LocalHours::where('location_id', $location->id)->get();
            
            $website_content = WebsiteContent::where('salon_id', $salon->id)->first();
            
            $check_open_status = $this->website_repo->checkOpenStatus($salon, $location_hours);
            
            $cat_list = $this->salon_repo->getActiveCategories($location);
            
            $location_photos = LocationPhotos::where('location_id', $location->id)->take(12)->get();

            return view('website.websiteLocation', ['salon' => $salon, 'selected_location' => $location, 'website_content' => $website_content, 'location_photos' => $location_photos, 'categories' => $cat_list, 'open_hours' => $location_hours, 'open_status' => $check_open_status['status']]);
        }
        
        return view('website.404', ['message' => trans('salon.website_not_found')]);
        
    }
    
    public function getWebsiteBlog() {
        
        if($salon = Salons::find(Auth::user()->salon_id)) {
            
            $blog_posts = BlogPost::where('salon_id', $salon->id)->get();
            
            return view('website.blog', ['salon' => $salon, 'blog_posts' => $blog_posts]);
            
        }
        
        return redirect()->back();
        
    }
    
    public function submitBlogPost(Request $request) {

        $blog_post = $this->website_repo->saveBlogPost($request->all());

        if($blog_post['status'] === 1) {
            return redirect()->back()->with('success_message', $blog_post['message']);
        }
        
        return redirect()->back()->with('error_message', $blog_post['message']);
           
    }
    
    public function updateSocialLinks(Request $request) {
        
        $links = $this->website_repo->updateSocialLinks($request->all());

        return ['status' => $links['status'], 'message' => $links['message']];
        
    }
    
    public function getBlogPost($salon, $blog) {
        
        $salon = Salons::where('unique_url', $salon)->first();
        
        $blog_post_list = BlogPost::where('salon_id', $salon->id)->take(20)->get();
        
        $blog_post = BlogPost::where('unique_url', $blog)->first();
        
        $blog_post_arr = [];
        
        if($blog_post != null) {
            
            return view('website.blogPost', ['salon' => $salon, 'all_posts' => $blog_post_list, 'blog_post' => $blog_post]);
            
        }
        
        return redirect()->back();
        
    }
    
    public function deleteBlogPost(Request $request) {
        
        $delete = $this->website_repo->deleteBlogPost($request->id);
        
        if($delete['status'] === 1) {
            return ['status' => 1, 'message' => $delete['message']];
        }
        
        return ['status' => 0, 'message' => $delete['message']];

    }
    
    public function getBlogPostContent($id) {
        
        if($blog_post = BlogPost::find($id)) {
            
            return ['status' => 1, 'post' => $blog_post];
            
        }
        
        return ['status' => 0];
        
    }
    
    public function getSalonBlog($salon_url) {
        
        $salon = Salons::where('unique_url', $salon_url)->first();
        
        if($salon != null) {
            
            $recent_posts = BlogPost::where('salon_id', $salon->id)->take(2)->get();
            
            return view('website.salonBlog', ['salon' => $salon, 'recent_posts' => $recent_posts]);
            
        }
        
        return redirect()->back();
        
    }
    
    public function uploadSliderImages(Request $request) {
        
        $validator = Validator::make($request->all(), WebsiteImages::$slider_rules);
        
        if ($validator->fails()) {
            return response($validator->errors()->all()[0], 500)
                  ->header('Content-Type', 'text/plain');
        }
        
        $slider_images = $this->website_repo->uploadSliderImages($request->all());
        
        if($slider_images['status'] === 1) {
            return response($slider_images['message'], 200)
                   ->header('Content-Type', 'text/plain');
        }
        
        return response($slider_images['message'], 500)
                   ->header('Content-Type', 'text/plain');
        
    }
    
    public function deleteSliderImage(Request $request) {

        $delete = $this->website_repo->deleteSliderImage($request->id);
        
        if($delete['status'] === 1) {
            return ['status' => 1, 'message' => $delete['message']];
        }
        
        return ['status' => 0, 'message' => $delete['message']];
        
    }
    
    public function saveAboutImage(Request $request) {

        $validator = Validator::make($request->all(), WebsiteContent::$about_image_rules);
        
        if($validator->fails()) {
            return ['status' => 0, 'message' => $validator->errors()->all()[0]];
        }
        
        $about_image = $this->website_repo->saveAboutImage($request->all());
        
        return ['status' => $about_image['status'], 'message' => $about_image['message']];
        
    }
    
    public function deleteAboutImage(Request $request) {
        
        $delete = $this->website_repo->deleteSliderImage($request->id);
        
        if($delete['status'] === 1) {
            return ['status' => 1, 'message' => $delete['message']];
        }
        
        return ['status' => 0, 'message' => $delete['message']];
        
    }
    
    public function loadBlogPosts($salon_id, $page) {
        
        $blog_posts = $this->website_repo->loadBlogPosts($salon_id, $page);
        
        return $blog_posts;
        
    }
    
    public function updateSliderPromo(Request $request) {

        $slider_promo = $this->website_repo->updateSliderPromo($request->all());

        return ['status' => $slider_promo['status'], 'message' => $slider_promo['message']];
        
    }
    
    public function getClientBooking($salon, $location = null) {

        $salon = Salons::where('unique_url', $salon)->first();

        $website_content = WebsiteContent::where('salon_id', $salon->id)->first();

        $location_list = Location::where('salon_id', $salon->id)->get();

        $first_location = $location_list[0]->id;

        $currency = $salon->currency;
        
        $booking_options = BookingPolicy::where('salon_id', $salon->id)->first();
        
        $calendar_options = CalendarOptions::where('salon_id', $salon->id)->first();
        
        $calendar_settings = CalendarSettings::where('salon_id', $salon->id)->first();

        $week_start = $salon->week_starting_on;
        
        if($week_start == 2) {
            $week_start = 0;
        } else {
            $week_start = 1;
        }

        $loyalty_message = null;
        if($location != null) {

            $location = Location::where('unique_url', $location)->first();

            if(Auth::user()) {
                $user = User::find(Auth::user()->id);
                $client_loyalty = $this->client_repo->checkLoyaltyProgram($user,$location);
                $loyalty_message = $client_loyalty;
            }

            $category_list = Category::where('location_id', $location->id)->where('active', 1)->get();
            $loyalty_program = LoyaltyPrograms::where('location_id', $location->id)->first();

            return view('website.clientBooking', ['salon' => $salon, 'location' => $location, 'website_content' => $website_content, 'booking_options' => $booking_options, 'location_list' => $location_list, 'first_location' => $first_location, 'week_start' => $week_start, 'client_loyalty' => $loyalty_message,
                'currency' => $currency, 'calendar_options' => $calendar_options, 'calendar_settings' => $calendar_settings, 'category_list' => $category_list, 'client_check' => 1, 'loyalty_program' => $loyalty_program]);
        }

        return view('website.clientBooking', ['salon' => $salon, 'location' => $location, 'website_content' => $website_content, 'booking_options' => $booking_options, 'location_list' => $location_list,
                                             'first_location' => $first_location, 'week_start' => $week_start, 'client_loyalty' => $loyalty_message,
                                             'currency' => $currency, 'calendar_options' => $calendar_options, 'calendar_settings' => $calendar_settings, 'client_check' => 0]);
        
    }

    public function getWebsiteSliderSettings() {

        $images = $this->website_repo->getGlobalImages();

        return view('website.admin.websiteSlider', ['images' => $images]);
    }

    public function uploadGlobalSliderImages(Request $request) {

        $images = $this->website_repo->uploadGlobalImages($request->all());

        if($images['status'] != 1) {
            return ['status' => 0, 'message' => $images['message']];
        }

        return ['status' => 1, 'message' => $images['message']];
    }

    public function updateSliderImages(Request $request) {

        $images = $this->website_repo->updateSliderImages($request->all());

        return ['status' => $images['status'], 'message' => $images['message']];

    }
    
}
