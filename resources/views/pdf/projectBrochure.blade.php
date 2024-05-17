<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <meta charset="UTF-8">
    <title>brochure</title>

</head>
<style type="text/css">
    body {
        padding: 0px;
        margin: 0px;
        overflow-x: hidden;
        font-family: "Poppins";
    }

    p,
    span.a,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        margin: 0px;
        padding: 0px;
        font-family: "Poppins";
    }

    p {
        font-size: 14px;
        color: #6f6d71 !important;
    }

    .header {
        position: relative !important;
    }

    .headerbannerImg {
        width: 100%;
        height: 400px;
        object-fit: cover;
    }

    .header-content {
        background: linear-gradient(0deg, white, white, #ffffffd6, transparent);
        /*padding: 30px;*/
        /*position: absolute !important;*/
        width: 100%;
        left: 0px;

        /*bottom: 0px;*/
    }

    .headTitle {
        font-size: 90px;
        color: #000000;
        text-align: center;
        font-weight: 600;
        display: block;
    }

    .pText {
        font-size: 36px;
        text-align: center;
        font-weight: 400;
        text-align: center;
        display: block;
    }

    .section {
        display: block;
        text-align: center;
        margin-bottom: 40px;
    }

    .headerSection {
        display: block;
        text-align: center;
    }

    .h1Title {
        color: #2876b8;
        font-size: 40px;
        font-weight: 600;
    }

    .letterSpacing-6 {
        letter-spacing: 6px;
    }

    .pText.smText {
        font-weight: 500;
        color: #202020;
    }

    .pText.LgText {
        color: #858585;
        font-size: 36px;
        text-transform: uppercase;
        font-size: 38px;
    }

    .spanTitle {
        color: #2876b8;
        font-weight: 600;
    }

    .rowArea {
        display: flex;
        gap: 15px;
        margin-top: 40px;
    }

    .clumBox {
        display: inline;
    }

    .clumImg {
        width: 100%;
        height: 100px;
        object-fit: cover;
    }

    .section.pad-100 {
        padding: 0px 100px;
    }

    .headerSection.pad-100 {
        padding: 0px 100px;
    }

    .descriptionSection.pad-100 {
        padding: 0px 100px;
    }

    .logoImg {
        width: 200px;
        object-fit: contain;
        position: absolute;
        top: 50px;
        right: 40px;
    }

    .arrrowIcon {
        width: 80px;
        height: 60px;
        object-fit: contain;
        object-position: left;
    }

    .vtTextBXox p {
        margin: 0px;
        color: #3f3f3f;
        font-size: 20px;
    }

    .vtTextBXox h3 {
        font-size: 20px;
        font-weight: 600;
        margin: 0px;
        color: #3f3f3f;
    }

    .text-primary {
        color: #136dae !important;
    }

    .sctionMdTitle {
        font-size: 28px;
        text-transform: uppercase;
        font-weight: 500;
    }

    .tableContainer {
        width: 100%;
        overflow-x: auto;
    }

    .tableContainer .priceTable {
        width: 100%;
    }

    .priceTable {
        width: 100%;
        margin-bottom: 30px;
    }

    table {
        caption-side: bottom;
        border-collapse: collapse;
    }

    thead {
        text-align: left;
    }

    .priceTable thead .tblThText {
        font-size: 20px;
        color: #3f3f3f;
        font-weight: 600;
        margin: 0px;
    }

    .priceTable tbody tr {
        border-bottom: 1px solid #8c8c8c;
    }

    tbody,
    td,
    tfoot,
    th,
    thead,
    tr {
        border-color: inherit;
        border-style: solid;
        border-width: 0;
    }

    .priceTable tbody .tblTdText {
        margin: 0px;
    }

    .tblTdText {
        margin-bottom: 0px;
    }

    .text-secondary {
        color: #6f6d71 !important;
    }

    table {
        display: table;
        text-indent: initial;
        border-spacing: 2px;
        border-color: gray;
    }

    tbody {
        vertical-align: middle;
        border-color: inherit;
    }

    .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        max-height: 600px;
    }

    .mb-2 {
        margin-bottom: 0.5rem !important;
    }

    .amenityImg {
        width: 120px;
        height: 120px;
        border: 3px solid #136dae;
        border-radius: 50%;
        overflow: hidden;
        padding: 25px;
    }

    .amenityImg img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .text-center {
        text-align: center !important;
    }

    .ps-2 {
        padding-left: 0.5rem !important;
    }

    .swiper-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
        z-index: 1;
        display: flex;
        transition-property: transform;
        transition-timing-function: var(--swiper-wrapper-transition-timing-function,
                initial);
        box-sizing: content-box;
    }

    .webText {
        color: #4c4c4c;
        font-weight: 400;
        display: block;
        text-align: center;
        font-size: 30px;
    }
</style>

<body>
    @php
        $exteriorGallery = $project->exteriorGallery;
        $interiorGallery = $project->interiorGallery;
        $gallery = array_merge($exteriorGallery, $interiorGallery);
        $bannerImage = $gallery[0];
        $exteriorGallery = $exteriorGallery;
        array_shift($gallery);
        $amenities = $project->amenities()->get()->toArray();
        [$amenitiePieces2, $amenitiePieces1] = array_chunk($amenities, ceil(count($amenities) / 2));
    @endphp
    <header class="header">
        <img src="{{ $bannerImage['path'] }}" class="headerbannerImg" />
        <img src="{{ asset('frontend/assets/images/logo.png') }}" class="logoImg" />
        <div class="header-content">
            <div class="headerSection">
                <table style="width:100%">
                    <tr>
                        <th>
                            <h1 class="h1Title" style="text-transform: uppercase;">{{ $project->title }}</h1>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            {!! $project->short_description !!}
                        </td>
                        @if ($project->qr)
                            <td>
                                <img src="{{ $project->qr }}" alt="{{ $project->qr }}" style="padding:5px">
                            </td>
                        @endif
                    </tr>
                </table>
            </div>
        </div>
    </header>

    <section class="">
        <table style="width:100%">
            <thead>
                <tr>
                    <td>
                        <h4>Starting Price</h4>
                    </td>
                    <td>
                        <h4>Available Units</h4>
                    </td>
                    <td>
                        <h4>Handover</h4>
                    </td>
                    @if ($project->permit_number)
                        <td>
                            <h4>Permit Number</h4>
                        </td>
                    @endif
                    <td>
                        <h4>Location</h4>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="vtTextBXox">
                            <h3 class="text-primary">AED {{ number_format($starting_price) }}</h3>
                        </div>
                    </td>
                    <td>
                        <div class="vtTextBXox">
                            <h3 class="text-primary"> {{ $bedrooms }} BR </h3>
                        </div>
                    </td>
                    <td>
                        <div class="vtTextBXox">
                            <h3 class="text-primary">{{ $handOver }}</h3>
                        </div>
                    </td>
                    @if ($project->permit_number)
                        <td>
                            <div class="vtTextBXox">
                                <h3 class="text-primary">{{ $project->permit_number }}</h3>
                            </div>
                        </td>
                    @endif


                    <td>
                        <div class="vtTextBXox">
                            <h3 class="text-primary">{{ $communityName }}</h3>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table><br />
    </section>

    <section class="" style="margin-top:10px; margin-bottom:10px">
        <table style="width:100%;">
            <thead>
                <tr>
                    <!--@foreach ($exteriorGallery as $key => $image)
-->
                    <!--@if ($key != 0 && $key < 5)
<td>-->
                    <!--  <div class="vtTextBXox">-->
                    <!--    <img src="{{ $image['path'] }}" class="clumImg">-->
                    <!--  </div>-->
                    <!--  </td>-->
                    <!--
@endif-->
                    <!--
@endforeach-->

                    <div style="display: inline-block;">
                        @foreach ($exteriorGallery as $key => $image)
                            @if ($key < 3)
                                <div class="vtTextBXox" style="padding:2px; display: inline-block;">
                                    <img src="{{ $image['path'] }}" height="110" style="display: inline-block;">
                                </div>
                            @endif
                        @endforeach
                    </div>


                </tr>
            </thead>
        </table>
    </section>

    <section class="">
        <table style="width:100%;">
            <thead>
                <tr>
                    <td>
                        <h4 class="sctionMdTitle text-primary">PROPERTY TYPE</h4>
                    </td>
                </tr>
            </thead>
        </table>
        <table style="width:100%;">
            <thead>
                <tr>
                    <td>
                        <h4>Unit Type </h4>
                    </td>
                    <td>
                        <h4>Property Type </h4>
                    </td>
                    <td>
                        <h4>Size </h4>
                    </td>
                    <td>
                        <h4>Bedroom</h4>
                    </td>
                    <td>
                        <h4>Price </h4>
                    </td>
                </tr>
            </thead>
            <tbody>
                @foreach ($project->subProjects as $sub)
                    <tr>
                        <td>
                            <p class="tblTdText text-secondary">{{ $sub->title }}</p>
                        </td>
                        <td>
                            <p class="tblTdText text-secondary">
                                {{ $sub->accommodation ? $sub->accommodation->name : null }}</p>
                        </td>
                        <td>
                            <p class="tblTdText text-secondary">{{ $sub->area }} {{ $area_unit }}</p>
                        </td>
                        <td>
                            <p class="tblTdText text-secondary  text-center">{{ $sub->bedrooms }}</p>
                        </td>
                        <td>
                            <p class="tblTdText text-secondary">AED {{ number_format(intval($sub->starting_price)) }}
                            </p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
    <section class="">
        @foreach ($project->mPaymentPlans as $paymentPlan)
            <table style="width:100%">
                <thead>
                    <tr>
                        <td colspan="3">
                            <div class="vtTextBXox">
                                <h4 class="text-primary sctionMdTitle" style="text-transform: uppercase;">
                                    {{ $paymentPlan->value }}</h4>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4>Payments</h4>
                        </td>
                        <td>
                            <h4>Percentage (%)</h4>
                        </td>
                        <!--<td>-->
                        <!--    <h4>Milestones</h4>-->
                        <!--</td>-->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paymentPlan->paymentPlans as $row)
                        <tr>
                            <td>
                                <p class="tblTdText text-secondary">{{ $row->value }}</p>
                                <!--<p class="tblTdText text-secondary">{{ $row->name }}</p> -->

                            </td>
                            <td>
                                <p class="tblTdText text-secondary">{{ $row->key }}</p>

                            </td>
                            <!--<td>-->
                            <!--    <p class="tblTdText text-secondary">{{ $row->value }}</p> -->

                            <!--</td>-->

                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </section>
    <section class>
        <table style="width:100%;">
            <thead>
                <tr>
                    <td>
                        <h4 class="sctionMdTitle text-primary">Amenities</h4>
                    </td>
                </tr>
            </thead>
        </table>

        <table style="width:100%;">
            <thead>
                @foreach ($amenitiePieces1 as $key => $amenitiePiece1)
                    <tr>
                        <td>
                            <p class="tblTdText text-secondary">&bull;{{ $amenitiePiece1['name'] }}
                            </p>
                        </td>
                        <td>
                            <p class="tblTdText text-secondary">&bull;{{ $amenitiePieces2[$key]['name'] }}
                            </p>
                        </td>
                    </tr>
                @endforeach
            </thead>
        </table>
    </section>

    <section>
        <br /><br />
        <table style="width:100%;">
            <thead>
                <tr>
                    <!--@foreach ($interiorGallery as $key => $image)
-->
                    <!--@if ($key != 0 && $key < 4)
<td>-->
                    <!--  <div class="vtTextBXox">-->
                    <!--    <img src="{{ $image['path'] }}" height="200" style="padding:10px">-->
                    <!--  </div>-->
                    <!--  </td>-->
                    <!--
@endif-->
                    <!--
@endforeach-->


                    <div style="display: inline-block;">
                        @foreach ($interiorGallery as $key => $image)
                            @if ($key < 3)
                                <div class="vtTextBXox" style="padding:2px; display: inline-block;">
                                    <img src="{{ $image['path'] }}" height="230" style="display: inline-block;">
                                </div>
                            @endif
                        @endforeach
                    </div>

                </tr>
            </thead>
        </table>
    </section>

    <section>
        <table style="width:100%;">
            <tr>
                <td style="width:70%">
                    {!! $project->developer->short_description !!}
                </td>
                <td style="width:30%">
                    <img src="{{ $project->developer->logo }}" width="200">
                </td>
            </tr>
        </table>
    </section>
    <section>
        <h5 class="pText mdText">For more info contact</h5>
        <h1 class="h1Title mdText mb-20" style="text-align:center">Toll Free 800 72 888 <span>sales@range.ae</span></h1>
        <span class="webText" style="text-align:center">www.range.ae</span>
    </section>
</body>

</html>
