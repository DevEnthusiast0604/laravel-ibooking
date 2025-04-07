<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/12/2020
 * Time: 11:33 PM
 */
$namespace = 'App\Modules\Frontend\Controllers';

Route::group([
    'namespace' => $namespace,
    'middleware' => ['web', 'auth', 'locale'],
], function () {
    Route::post('add-wishlist', 'WishlistController@addWishlistAction');
});

Route::group([
    'module' => 'Frontend',
    'namespace' => $namespace,
    'middleware' => ['web', 'guest', 'locale'],
], function () {
    Route::get('login', 'UserController@loginView')->name('login');
    Route::get('admin', function (){
       return redirect()->route('login');
    });
    Route::get('register', 'UserController@registerView')->name('register');
    Route::get('password/reset', 'UserController@showLinkRequestForm')->name('password.request');
    Route::get('password/reset/{token}', 'UserController@showResetForm')->name('password.reset');

    Route::get('/auth/redirect/{provider}', 'SocialController@redirect');
    Route::get('/callback/{provider}', 'SocialController@callback');
});

Route::group([
    'namespace' => 'App\Http\Controllers\Auth',
    'middleware' => ['web', 'auth', 'locale'],
], function () {
    Route::get('logout', 'LoginController@logout');
});

Route::group([
    'namespace' => $namespace,
    'middleware' => ['web', 'locale'],
], function () {
    Route::get('/', 'PageController@getHomePage')->name('home');
    Route::get('become-a-partner', 'PageController@getBecomePartnerPage')->name('become-a-partner');
    Route::post('become-a-partner', 'UserController@becomeAPartnerAction');

    //Availability
    Route::post('fetch-calendar-availability', 'AvailabilityController@fetchAvailabilityAction');
    Route::post('get-real-price', 'AvailabilityController@getRealPriceAction');

    Route::get('checkout', 'OrderController@checkoutView')->name('checkout');
    Route::post('checkout', 'OrderController@checkoutAction');
    Route::get('payment-checking', 'OrderController@paymentChecking')->name('payment-checking');
    Route::get('complete-order', 'OrderController@completeOrder')->name('complete-order');

    //Comment
    Route::post('add-comment', 'CommentController@addCommentAction');

    //Page
	Route::get('page/{slug}', 'PageController@singleView')->name('page-single');

	//Post
    Route::get('post/{slug}', 'PostController@singleView')->name('post-single');
    Route::get('blog', 'PostController@blogView')->name('blog');
    Route::get('category/{category_slug}', 'PostController@categoryView')->name('category');
    Route::get('tag/{tag_slug}', 'PostController@tagView')->name('tag');
    Route::get('contact-us', 'PageController@contactUsView')->name('contact-us');
    Route::post('contact-us', 'PageController@contactUsAction');

    //Coupon
    Route::post('apply-coupon', 'CouponController@applyCouponAction');
    Route::post('remove-coupon', 'CouponController@removeCouponAction');

    //Notification
    Route::post('update-check-notification', 'NotificationController@updateCheckAction');

    //SEO
    Route::get('sitemap.xml', 'SeoController@createSitemap');
    Route::get('sitemap-{service}.xml', 'SeoController@createSitemapService');
    Route::get('robots.txt', 'SeoController@robotsView')->name('robots.txt');

    Route::get('author/{id}', 'UserController@authorView')->name('author.view');
});

//Car
Route::group([
    'namespace' => $namespace,
    'middleware' => ['web', 'locale', 'car_enable'],
], function () {
    Route::get('car', 'CarController@carPageView')->name('car');
    Route::get('car-search', 'CarController@carSearchView')->name('car-search');
    Route::post('car-search', 'CarController@carSearchAction');
    Route::get('car/{slug}', 'CarController@singleView')->name('car-single');
    Route::post('car-add-cart', 'CarController@addCartAction');
    Route::post('car-fetch-calendar-availability', 'CarController@fetchAvailabilityAction');
    Route::post('car-get-real-price', 'CarController@getRealPriceAction');
    Route::post('car-send-enquiry', 'CarController@sendEnquiryAction');
});

//Apartment
Route::group([
    'namespace' => $namespace,
    'middleware' => ['web', 'locale', 'apartment_enable'],
], function () {
    Route::get('apartment', 'ApartmentController@apartmentPageView')->name('apartment');
    Route::get('apartment-search', 'ApartmentController@apartmentSearchView')->name('apartment-search');
    Route::post('apartment-search', 'ApartmentController@apartmentSearchAction');
    Route::get('apartment/{slug}', 'ApartmentController@singleView')->name('apartment-single');
    Route::post('apartment-add-cart', 'ApartmentController@addCartAction');
    Route::post('apartment-fetch-calendar-availability', 'ApartmentController@fetchAvailabilityAction');
    Route::post('apartment-fetch-time', 'ApartmentController@fetchTimeAction');
    Route::post('apartment-get-real-price', 'ApartmentController@getRealPriceAction');
    Route::post('apartment-send-enquiry', 'ApartmentController@sendEnquiryAction');
});

//Hotel
Route::group([
    'namespace' => $namespace,
    'middleware' => ['web', 'locale', 'hotel_enable'],
], function () {
    Route::get('hotel', 'HotelController@hotelPageView')->name('hotel');
    Route::get('hotel-search', 'HotelController@hotelSearchView')->name('hotel-search');
    Route::post('hotel-search', 'HotelController@hotelSearchAction');
    Route::get('hotel/{slug}', 'HotelController@singleView')->name('hotel-single');
    Route::post('hotel-send-enquiry', 'HotelController@sendEnquiryAction');
    Route::post('room-search', 'RoomController@searchRoomAction');
    Route::post('room-detail', 'RoomController@roomDetailAction');
    Route::post('room-get-real-price', 'RoomController@getRealPriceAction');
    Route::post('hotel-add-cart', 'HotelController@addCartAction');
});

Route::group([
	'namespace' => $namespace
], function () {
	Route::get('installer/{step?}', 'InstallerController@stepOneView')->name('installer');
	Route::post('installer/config-database', 'InstallerController@configDatabaseAction');
	Route::post('installer/import-data', 'InstallerController@importDataAction');
});

//Space
Route::group([
	'namespace' => $namespace,
	'middleware' => ['web', 'locale', 'space_enable'],
], function () {
	Route::get('space', 'SpaceController@spacePageView')->name('space');
	Route::get('space-search', 'SpaceController@spaceSearchView')->name('space-search');
	Route::post('space-search', 'SpaceController@spaceSearchAction');
	Route::get('space/{slug}', 'SpaceController@singleView')->name('space-single');
	Route::post('space-add-cart', 'SpaceController@addCartAction');
	Route::post('space-fetch-calendar-availability', 'SpaceController@fetchAvailabilityAction');
	Route::post('space-fetch-time', 'SpaceController@fetchTimeAction');
	Route::post('space-get-real-price', 'SpaceController@getRealPriceAction');
	Route::post('space-send-enquiry', 'SpaceController@sendEnquiryAction');
});

//Tour
Route::group([
    'namespace' => $namespace,
    'middleware' => ['web', 'locale', 'tour_enable'],
], function () {
    Route::get('tour', 'TourController@tourPageView')->name('tour');
    Route::get('tour-search', 'TourController@tourSearchView')->name('tour-search');
    Route::post('tour-search', 'TourController@tourSearchAction');
    Route::get('tour/{slug}', 'TourController@singleView')->name('tour-single');
    Route::post('tour-add-cart', 'TourController@addCartAction');
    Route::post('tour-fetch-calendar-availability', 'TourController@fetchAvailabilityAction');
    Route::post('tour-get-real-price', 'TourController@getRealPriceAction');
    Route::post('tour-send-enquiry', 'TourController@sendEnquiryAction');
});

//beauty services
Route::group([
   'namespace' => $namespace,
   'middleware' => ['web', 'locale', 'beauty_enable'],
], function () {
   Route::get('beauty-services', 'BeautyController@beautyPageView')->name('beauty-services');
   Route::get('beauty-search', 'BeautyController@beautySearchView')->name('beauty-search');
   Route::post('beauty-search', 'BeautyController@beautySearchAction');
   Route::get('beauty-service/{slug}', 'BeautyController@singleView')->name('beauty-single');
   Route::get('beauty-get-booking-form', 'BeautyController@getBookingForm');
   Route::post('beauty-add-cart', 'BeautyController@addCartAction');
   Route::post('beauty-fetch-calendar-availability', 'BeautyController@fetchAvailabilityAction');
   Route::post('beauty-fetch-time', 'BeautyController@fetchTimeAction');
   Route::post('beauty-get-real-price', 'BeautyController@getRealPriceAction');
   Route::post('beauty-send-enquiry', 'BeautyController@sendEnquiryAction');
});