<title>{{ $title }}</title>
@include("landingpage.partials/header")

<div class="body_wrapper">
    <section class="breadcrumb_area">
        <img class="breadcrumb_shap" src="landingpage/img/breadcrumb/banner_bg.png" alt="">
        <div class="container">
            <div class="breadcrumb_content text-center">
                <h1 class="f_p f_700 f_size_50 w_color l_height50 mb_20">Support Q&A</h1>
                <p class="f_400 w_color f_size_16 l_height26">We will improve and serve you better which is <br> based on your feedback and feature request.</p>
            </div>
        </div>
    </section>

    <section class="faq_area bg_color sec_pad">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 pr_50">
                    <div class="faq_tab">
                        <h4 class="f_p t_color3 f_600 f_size_22 mb_40">Quick Navigation</h4>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="purchas-tab" data-toggle="tab" href="#purchas" role="tab" aria-controls="purchas" aria-selected="true">FAQ</a>
                            </li>
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link" id="returns-tab" data-toggle="tab" href="#returns" role="tab" aria-controls="returns" aria-selected="false">Returns</a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link" id="price-tab" data-toggle="tab" href="#price" role="tab" aria-controls="price" aria-selected="false">Pricing & Support</a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link" id="care-tab" data-toggle="tab" href="#care" role="tab" aria-controls="care" aria-selected="false">Care & Repair</a>--}}
{{--                            </li>--}}
                        </ul>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="tab-content faq_content" id="myTabContent">
                        <div class="tab-pane fade show active" id="purchas" role="tabpanel" aria-labelledby="purchas-tab">
                            <h3 class="f_p f_size_22 f_500 t_color3 mb_20">FAQ</h3>
                            <div id="accordion">
                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Is it easy to build a website?	<i class="ti-plus"></i><i class="ti-minus"></i>
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">
                                            Yes. Magicak offers a few different ways to create your own free website, so you can choose the creation process that works best for you. Need to get online fast? Answer a few simple questions and Magicak ADI (Artificial Design Intelligence) will build a professional website for you in under 10 minutes. If you want 100% design freedom, start from scratch and drag and drop the website design features you need in the Magicak Editor.
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingTwo">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                Can I create a website without knowing how to code?	<i class="ti-plus"></i><i class="ti-minus"></i>
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                        <div class="card-body">
                                            Absolutely. Magicak is user-friendly and makes it possible to build a professional website without knowing how to code. In the Magicak Editor, you can drag and drop any feature you want and customize it to match the look and feel of your site. Of course, if you do know how to code, you can add advanced functionality to your site with Corvid.
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingThree">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                How do I make my site mobile friendly?	<i class="ti-plus"></i><i class="ti-minus"></i>
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                        <div class="card-body">
                                            Your Magicak website automatically comes with a mobile-optimized version that looks great on smaller screens. The Magicak Mobile Editor makes it easy to customize your mobile site even more.
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingfour">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapsefour" aria-expanded="false" aria-controls="collapsefour">
                                                How do I create a free website with a custom domain?	<i class="ti-plus"></i><i class="ti-minus"></i>
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapsefour" class="collapse" aria-labelledby="headingfour" data-parent="#accordion">
                                        <div class="card-body">
                                            You can create a free website with Magicak that comes with a Magicak domain. To instantly look more professional online, get a custom domain name. It adds credibility to your brand and helps visitors find you online. You can start building your brand by using your domain in a custom email address (info@mystunningwebsite.com), your social channels, email marketing campaigns and more.
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingfive">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapsefive" aria-expanded="false" aria-controls="collapsefive">
                                                Should I use a website builder or hire a web developer?	<i class="ti-plus"></i><i class="ti-minus"></i>
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapsefive" class="collapse" aria-labelledby="headingfive" data-parent="#accordion">
                                        <div class="card-body">
                                            The free Magicak website builder is intuitive to use. Looking for a specific web service? Explore the Magicak Marketplace to find a certified freelancer or agency at a price that fits your budget.
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingsix">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapsefive" aria-expanded="false" aria-controls="collapsesix">
                                                How do I build and host my site for free?	<i class="ti-plus"></i><i class="ti-minus"></i>
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapsesix" class="collapse" aria-labelledby="headingsix" data-parent="#accordion">
                                        <div class="card-body">
                                            When you create a free website with Magicak, you get reliable, scalable and free web hosting. All your web content will be stored on secure servers located around the world. So no matter where your visitors come from, your site will load fast.
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingseven">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseseven" aria-expanded="false" aria-controls="collapseseven">
                                                Can I use a website builder to create a landing page?	 <i class="ti-plus"></i><i class="ti-minus"></i>
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseseven" class="collapse" aria-labelledby="headingseven" data-parent="#accordion">
                                        <div class="card-body">
                                            Absolutely! You can create any kind of website with Magicak. Explore beautiful HTML landing page templates to get started today.
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingeight">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseeight" aria-expanded="false" aria-controls="collapseeight">
                                                How can I optimize my site for SEO on Magicak?	  <i class="ti-plus"></i><i class="ti-minus"></i>
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseeight" class="collapse" aria-labelledby="headingeight" data-parent="#accordion">
                                        <div class="card-body">
                                            All Magicak sites come with a suite of built-in SEO tools to help you compete in organic search. Get listed on Google in seconds with our Google integration. Customize your site’s meta tags, URL slugs, canonical tags, structured data, robots.txt file and more. Learn more
                                        </div>
                                    </div>
                                </div>
                            </div>
{{--                            <h3 class="f_p f_size_22 f_500 t_color3 mb_20 mt_100">How to pay</h3>--}}
{{--                            <div id="accordion2">--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="headingsix">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapsesix" aria-expanded="false" aria-controls="collapsesix">--}}
{{--                                                How do I repair an item?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                    <div id="collapsesix" class="collapse" aria-labelledby="headingsix" data-parent="#accordion2">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="headingseven">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseseven" aria-expanded="false" aria-controls="collapseseven">--}}
{{--                                                Where can I find instructions on how to use my watch?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                    <div id="collapseseven" class="collapse" aria-labelledby="headingseven" data-parent="#accordion2">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="headingeight">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseeight" aria-expanded="false" aria-controls="collapseeight">--}}
{{--                                                Is there a warranty on my item?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                    <div id="collapseeight" class="collapse" aria-labelledby="headingeight" data-parent="#accordion2">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="headingnine">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapsenine" aria-expanded="false" aria-controls="collapsenine">--}}
{{--                                                Is there a warranty on my item?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                    <div id="collapsenine" class="collapse" aria-labelledby="headingnine" data-parent="#accordion2">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
                        <div class="tab-pane fade" id="returns" role="tabpanel" aria-labelledby="returns-tab">
{{--                            <h3 class="f_p f_size_22 f_500 t_color3 mb_20">How to purchase</h3>--}}
{{--                            <div id="accordion3">--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="heading10">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapse10" aria-expanded="true" aria-controls="collapse10">--}}
{{--                                                How do I repair an item?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}

{{--                                    <div id="collapse10" class="collapse show" aria-labelledby="heading10" data-parent="#accordion3">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="heading11">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse11" aria-expanded="false" aria-controls="collapse11">--}}
{{--                                                Where can I find instructions on how to use my watch?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                    <div id="collapse11" class="collapse" aria-labelledby="heading11" data-parent="#accordion3">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="heading12">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse12" aria-expanded="false" aria-controls="collapse12">--}}
{{--                                                Is there a warranty on my item?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                    <div id="collapse12" class="collapse" aria-labelledby="heading12" data-parent="#accordion3">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="heading13">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse13" aria-expanded="false" aria-controls="collapse13">--}}
{{--                                                Is there a warranty on my item?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                    <div id="collapse13" class="collapse" aria-labelledby="heading13" data-parent="#accordion3">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="heading14">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse14" aria-expanded="false" aria-controls="collapse14">--}}
{{--                                                Is there a warranty on my item?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                    <div id="collapse14" class="collapse" aria-labelledby="heading14" data-parent="#accordion3">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>

                        <div class="tab-pane fade" id="price" role="tabpanel" aria-labelledby="price-tab">
{{--                            <h3 class="f_p f_size_22 f_500 t_color3 mb_20">How to purchase</h3>--}}
{{--                            <div id="accordion4">--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="heading15">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapse15" aria-expanded="true" aria-controls="collapse15">--}}
{{--                                                How do I repair an item?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}

{{--                                    <div id="collapse15" class="collapse show" aria-labelledby="heading15" data-parent="#accordion4">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="heading16">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse16" aria-expanded="false" aria-controls="collapse16">--}}
{{--                                                Where can I find instructions on how to use my watch?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                    <div id="collapse16" class="collapse" aria-labelledby="heading16" data-parent="#accordion4">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="heading17">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse17" aria-expanded="false" aria-controls="collapse17">--}}
{{--                                                Is there a warranty on my item?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                    <div id="collapse17" class="collapse" aria-labelledby="heading17" data-parent="#accordion4">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="heading18">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse18" aria-expanded="false" aria-controls="collapse18">--}}
{{--                                                Is there a warranty on my item?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                    <div id="collapse18" class="collapse" aria-labelledby="heading18" data-parent="#accordion4">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="heading19">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse19" aria-expanded="false" aria-controls="collapse19">--}}
{{--                                                Is there a warranty on my item?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                    <div id="collapse19" class="collapse" aria-labelledby="heading19" data-parent="#accordion4">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <h3 class="f_p f_size_22 f_500 t_color3 mb_20 mt_100">How to pay</h3>--}}
{{--                            <div id="accordion5">--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="heading20">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse20" aria-expanded="false" aria-controls="collapse20">--}}
{{--                                                How do I repair an item?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                    <div id="collapse20" class="collapse" aria-labelledby="heading20" data-parent="#accordion5">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="heading21">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse21" aria-expanded="false" aria-controls="collapse21">--}}
{{--                                                Where can I find instructions on how to use my watch?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                    <div id="collapse21" class="collapse" aria-labelledby="heading21" data-parent="#accordion5">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="heading22">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse22" aria-expanded="false" aria-controls="collapse22">--}}
{{--                                                Is there a warranty on my item?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                    <div id="collapse22" class="collapse" aria-labelledby="heading22" data-parent="#accordion5">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="heading23">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse23" aria-expanded="false" aria-controls="collapse23">--}}
{{--                                                Is there a warranty on my item?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                    <div id="collapse23" class="collapse" aria-labelledby="heading23" data-parent="#accordion5">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
                        <div class="tab-pane fade" id="care" role="tabpanel" aria-labelledby="care-tab">
{{--                            <h3 class="f_p f_size_22 f_500 t_color3 mb_20">How to purchase</h3>--}}
{{--                            <div id="accordion6">--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="heading24">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapse24" aria-expanded="true" aria-controls="collapse24">--}}
{{--                                                How do I repair an item?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}

{{--                                    <div id="collapse24" class="collapse show" aria-labelledby="heading24" data-parent="#accordion6">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="heading25">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse25" aria-expanded="false" aria-controls="collapse25">--}}
{{--                                                Where can I find instructions on how to use my watch?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                    <div id="collapse25" class="collapse" aria-labelledby="heading25" data-parent="#accordion6">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="heading26">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse26" aria-expanded="false" aria-controls="collapse26">--}}
{{--                                                Is there a warranty on my item?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                    <div id="collapse26" class="collapse" aria-labelledby="heading26" data-parent="#accordion6">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="heading27">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse27" aria-expanded="false" aria-controls="collapse27">--}}
{{--                                                Is there a warranty on my item?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                    <div id="collapse27" class="collapse" aria-labelledby="heading27" data-parent="#accordion6">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="card">--}}
{{--                                    <div class="card-header" id="heading28">--}}
{{--                                        <h5 class="mb-0">--}}
{{--                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse28" aria-expanded="false" aria-controls="collapse28">--}}
{{--                                                Is there a warranty on my item?<i class="ti-plus"></i><i class="ti-minus"></i>--}}
{{--                                            </button>--}}
{{--                                        </h5>--}}
{{--                                    </div>--}}
{{--                                    <div id="collapse28" class="collapse" aria-labelledby="heading28" data-parent="#accordion6">--}}
{{--                                        <div class="card-body">--}}
{{--                                            Why I say old chap that is spiffing pukka, bamboozled wind up bugger buggered zonked hanky panky a blinding shot the little rotter, bubble and squeak vagabond cheeky bugger at public school pardon you bloke the BBC. Tickety-boo Elizabeth plastered matie.!--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

@include("landingpage.partials/footer")
