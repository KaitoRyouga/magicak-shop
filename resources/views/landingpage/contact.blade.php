<title>{{ $title }}</title>
@include("landingpage.partials/header")

<div class="body_wrapper">
    <section class="breadcrumb_area">
        <img class="breadcrumb_shap" src="landingpage/img/breadcrumb/banner_bg.png" alt="">
        <div class="container">
            <div class="breadcrumb_content text-center">
                <h1 class="f_p f_700 f_size_50 w_color l_height50 mb_20">Contac Us</h1>
                <p class="f_400 w_color f_size_16 l_height26">Why I say old chap that is spiffing off his nut arse pear shaped plastered<br> Jeffrey bodge barney some dodgy.!!</p>
            </div>
        </div>
    </section>
    <section class="mapbox2">
{{--        <div id="mapBox" class="row m0" data-lat="1.2756998" data-lon="103.8436211" data-zoom="17" data-info="10 ANSON ROAD #27-12 INTERNATIONAL PLAZA SINGAPORE (079903)." data-marker="landingpage/img/map.png" data-mlat="1.2756998" data-mlon="103.8436211">--}}
{{--        </div>--}}
        <iframe width="100%" height="440" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" id="gmap_canvas" src="https://maps.google.com/maps?width=1080&amp;height=540&amp;hl=en&amp;q=International%20Plaza%20Singapore%20079903%20Singapore+()&amp;t=&amp;z=17&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe> <script type='text/javascript' src='https://embedmaps.com/google-maps-authorization/script.js?id=7b866113e1cbbd102edc5fe066980b90678e2e94'></script>
    </section>
    <section class="contact_info_area sec_pad bg_color">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 pr-0">
                    <div class="contact_info_item">
                        <h6 class="f_p f_size_20 t_color3 f_500 mb_20">Office Address</h6>
                        <p class="f_400 f_size_15">10 ANSON ROAD</p>
                        <p class="f_400 f_size_15">#27-12</p>
                        <p class="f_400 f_size_15">INTERNATIONAL PLAZA</p>
                        <p class="f_400 f_size_15">SINGAPORE (079903)</p>
                    </div>
                    <div class="contact_info_item">
                        <h6 class="f_p f_size_20 t_color3 f_500 mb_20">Contact Info</h6>
                        <p class="f_400 f_size_15"><span class="f_400 t_color3">Phone:</span> <a href="tel:6582366656">(+65) 8236 6656</a></p>
                        <p class="f_400 f_size_15"><span class="f_400 t_color3">Email:</span> <a href="mailto:support@magicak.com">support@magicak.com</a></p>
                    </div>
                </div>
                <div class="col-lg-8 offset-lg-1">
                    <div class="contact_form">
                        <form action="contact_process.php" class="contact_form_box" method="post" id="contactForm" novalidate="novalidate">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group text_box">
                                        <input type="text" id="name" name="name" placeholder="Your Name">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group text_box">
                                        <input type="text" name="email" id="email" placeholder="Your Email">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group text_box">
                                        <input type="text" id="subject" name="subject" placeholder="Subject">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group text_box">
                                        <select name="type">
                                            <option value="Starting your Magicak account">Starting your Magicak account</option>
                                            <option value="Adding and editing pages and blocks">Adding and editing pages and blocks</option>
                                            <option value="Billing and paying for Magicak">Billing and paying for Magicak</option>
                                            <option value="Commerce and online stores">Commerce and online stores</option>
                                            <option value="Connecting email services to your site">Connecting email services to your site</option>
                                            <option value="Designing your site and managing your template">Designing your site and managing your template</option>
                                            <option value="Email Campaigns">Email Campaigns</option>
                                            <option value="Galleries, images, and video">Galleries, images, and video</option>
                                            <option value="Logging in and managing your account">Logging in and managing your account</option>
                                            <option value="Purchase, connect, or transfer a domain">Purchase, connect, or transfer a domain</option>
                                            <option value="Marketing, media or press inquiries">Marketing, media or press inquiries</option>
                                            <option value="Member Areas">Member Areas</option>
                                            <option value="Outages and errors">Outages and errors</option>
                                            <option value="Magicak Mobile Apps">Magicak Mobile Apps</option>
                                            <option value="Magicak Scheduling">Magicak Scheduling</option>
                                            <option value="SEO, social, and site analytics">SEO, social, and site analytics</option>
                                            <option value="Privacy and security">Privacy and security</option>
                                            <option value="Account Recovery">Account Recovery</option>
                                            <option value="Delete My Account">Delete My Account</option>
                                            <option value="Delete My Account Request Verification Form">Delete My Account Request Verification Form</option>
                                            <option value="Deceased Owner Form">Deceased Owner Form</option>
                                            <option value="Enterprise Inquiry">Enterprise Inquiry</option>
                                            <option value="Request help with a domain move">Request help with a domain move</option>
                                            <option value="Help and support during the Coronavirus Pandemic">Help and support during the Coronavirus Pandemic</option>
                                            <option value="Request a refund">Request a refund</option>
                                            <option value="Magicak Forum">Magicak Forum</option>
                                            <option value="Terms of service and policy updates">Terms of service and policy updates</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group text_box">
                                        <textarea name="message" id="message" cols="30" rows="10" placeholder="Enter Your Message . . ."></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group text_box">
                                        <input type="file" id="file" name="file" placeholder="Select a file">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn_three">Send Message</button>
                        </form>
                        <div id="success">Your message succesfully sent!</div>
                        <div id="error">Opps! There is something wrong. Please try again</div>
                    </div>
                </div>
            </div>
        </div>
    </section>



</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB13ZAvCezMx5TETYIiGlzVIq65Mc2FG5g"></script>
<script src="{{ asset ("landingpage/js/gmaps.min.js") }}"></script>
<script src="{{ asset ("landingpage/js/plugins.js") }}"></script>

@include("landingpage.partials/footer")
