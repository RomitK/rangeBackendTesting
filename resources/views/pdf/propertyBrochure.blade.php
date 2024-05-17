<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <meta charset="UTF-8">
    <title>brochure</title>

</head>
<style type="text/css">
    .page_break {
        page-break-before: always;
    }

    body {
        padding: 0px;
        margin: 0px;
        overflow-x: hidden;
        font-family: 'Poppins';
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
        font-family: 'Poppins';
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

    .tblTitleTd {
        width: 100%;
        font-size: 22px;
        color: #2879bc;
        font-weight: 600;
        padding-bottom: 15px;
    }

    .gridMainTable {
        width: 100%;
        margin-bottom: 30px;
    }

    .gridTable {
        width: 100%;
    }

    .cell3Td {
        width: 60%;
    }

    .cell2Td {
        width: 40%;
    }

    .clm3BarTbl {
        width: 60%;
    }

    .clmTbl {
        width: 100%;
    }

    .clum3Bar {
        width: 60%;
    }

    .clum2Bar {
        width: 40%;
    }

    .clum3Bar.bg-primery {
        background-color: #2879bc;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        padding: 3px 5px;
    }

    .clum2Bar.bg-gray {
        background-color: #f7f8f9;
        color: #01020a;
        font-size: 13px;
        padding: 3px 5px;
        font-weight: 400;
    }

    .htFix {
        height: 36px;
        vertical-align: baseline;
    }

    .cell3Td.rightBdr {
        border-right: 10px solid #fff;
    }

    .cell2Td.leftBdr {
        border-left: 10px solid #fff;
    }

    .cell3Td.leftBdr {
        border-left: 10px solid #fff;
    }

    .cell2Td.rightBdr {
        border-right: 10px solid #fff;
    }

    .clum2Bar.bg-primery {
        background-color: #2879bc;
        color: #fff;
        font-size: 14px;
        font-weight: 500;
        padding: 3px 5px;
    }

    .clum3Bar.bg-gray {
        background-color: #f7f8f9;
        color: #01020a;
        font-size: 13px;
        padding: 3px 5px;
        font-weight: 400;
    }

    .textArea {
        font-size: 14px;
        color: #01020a;
        font-style: italic;
    }
</style>

<body>
    @php
        $handOver = null;
        if ($property->project) {
            $dateStr = $property->project->completion_date;
            $month = date('n', strtotime($dateStr));
            $yearQuarter = ceil($month / 3);
            $handOver = 'Q' . $yearQuarter . ' ' . date('Y', strtotime($dateStr));
        }
        $a_fees = $property->price / 0.04;
        $b_fees = 540;
        $c_fees = 1000;
        $d_fees = 1000;
        $subTotal = $a_fees + $b_fees + $c_fees + $d_fees;
        $total = $property->price + $subTotal;
        if ($property->property_source == 'xml') {
            $gallery = $property->propertygallery->map(function ($img) {
                return [
                    'id' => 'gallery_' . $img->id,
                    'path' => $img->galleryimage,
                ];
            });
        } else {
            $gallery = $property->subImages;
        }
        if (count($gallery) > 0) {
            $bannerImage = $property->getMedia('mainImages')->first();
            $bannerImage = $bannerImage->getUrl();
        }

        array_shift($gallery);
        $amenities = $property->amenities()->get()->toArray();

        [$amenitiePieces2, $amenitiePieces1] = array_chunk($amenities, ceil(count($amenities) / 2));
    @endphp
    <header class="header">

        @if (isset($bannerImage))
            <img src="{{ $bannerImage }}" class="headerbannerImg" />
        @endif
        <img src="{{ asset('frontend/assets/images/logo.png') }}" class="logoImg" />
        <div class="header-content">
            <div class="headerSection">
                <table style="width:100%">
                    <tr>
                        <th colspan="2">
                            <h4 class="h1Title" style="line-height: 1;">{{ $property->name }}</h5>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            {{ $property->short_description }}
                        </td>

                        @if ($property->project->qr)
                            <td>
                                <img src="{{ $property->project->qr }}" alt="{{ $property->project->qr }}"
                                    style="padding:10px">
                            </td>
                        @endif

                    </tr>
                </table>
            </div>
        </div>
    </header>

    <section class>

        <table class="gridMainTable">
            <tr>
                <td class="tblTitleTd sctionMdTitle">
                    Property Details
                </td>

            </tr>
            <tr>
                <td style="width:100%">
                    <table class="gridTable">
                        <td class="cell3Td rightBdr">
                            <table class="clmTbl">
                                <tr>
                                    <td class="clum2Bar bg-primery">Product Name</td>
                                    <td class="clum3Bar bg-gray">{{ $property->name }}</td>
                                </tr>
                                <tr>
                                    <td class="clum2Bar bg-primery">Community Name</td>
                                    <td class="clum3Bar bg-gray">{{ $property->project->mainCommunity->name }}</td>
                                </tr>
                                <tr>
                                    <td class="clum2Bar bg-primery">Product Status</td>
                                    <td class="clum3Bar bg-gray">{{ $property->completionStatus->name }}</td>
                                </tr>
                                <tr>
                                    <td class="clum2Bar bg-primery">Anticipated Completion Date</td>
                                    <td class="clum3Bar bg-gray">{{ $handOver }}</td>
                                </tr>
                                @if ($property->project)
                                    <tr>
                                        <td class="clum3Bar bg-primery">Permit Number</td>
                                        <td class="clum2Bar bg-gray">{{ $property->project->permit_number }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="clum2Bar bg-primery">Gross Price</td>
                                    <td class="clum3Bar bg-gray">AED {{ number_format($property->price) }}</td>
                                </tr>

                            </table>
                        </td>

                    </table>
                </td>
            </tr>
        </table>
        <table class="gridMainTable">
            <tr>
                <td class="tblTitleTd sctionMdTitle">
                    Unit Details

                </td>

            </tr>
            <tr>
                <td style="width:100%">
                    <table class="gridTable">
                        <td class="cell2Td rightBdr">
                            <table class="clmTbl">
                                <tr>
                                    <td class="clum2Bar bg-primery">Unit</td>
                                    <td class="clum3Bar bg-gray">{{ $property->subProject->title }}</td>
                                </tr>
                                <tr>
                                    <td class="clum2Bar bg-primery">Unit Type</td>
                                    <td class="clum3Bar bg-gray">{{ $property->accommodations->name }}</td>
                                </tr>
                                <tr>
                                    <td class="clum2Bar bg-primery">Area (sqft)</td>
                                    <td class="clum3Bar bg-gray">{{ $property->area }}</td>
                                </tr>

                            </table>
                        </td>
                        <td class="cell3Td leftBdr">
                            <table class="clmTbl">
                                <tr>
                                    <td class="clum3Bar bg-primery">View Type</td>
                                    <td class="clum2Bar bg-gray">{{ $property->primary_view }}</td>
                                </tr>
                                <tr>
                                    <td class="clum3Bar bg-primery">Bedrooms</td>
                                    <td class="clum2Bar bg-gray">{{ $property->bedrooms }}</td>
                                </tr>

                                <tr>
                                    <td class="clum3Bar bg-primery">Parking</td>
                                    <td class="clum2Bar bg-gray">{{ $property->parking_space }}</td>
                                </tr>

                            </table>
                        </td>
                    </table>
                </td>
            </tr>
        </table>
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
                            <p class="tblTdText text-secondary" style="list-style-type: disc;">&bull;
                                {{ $amenitiePiece1['name'] }} </p>
                        </td>
                        <td>
                            <p class="tblTdText text-secondary" style="list-style-type: disc;">&bull;
                                {{ $amenitiePieces2[$key]['name'] }} </p>
                        </td>
                    </tr>
                @endforeach
            </thead>
        </table>
    </section>
    <section class style="margin-top:10px; margin-bottom:10px">
        <table style="width:100%;">
            <thead>
                <tr>
                    {{ $property->project->title }}
                </tr>

                <tr>
                    {!! $property->project->short_description->render !!} <br />
                </tr>
                <tr>

                    <div style="display: inline-block;">
                        @foreach ($property->project->exteriorGallery as $key => $image)
                            @if ($key < 3)
                                <!--<div class="vtTextBXox" style="padding:2px; display: inline-block;">-->
                                <!--    <img src="{{ $image['path'] }}" height="100" style="display: inline-block;">-->
                                <!--</div>-->

                                <div class="vtTextBXox" style="padding:2px; display: inline-block;">
                                    <img src="{{ $image['path'] }}" height="100" style="display: inline-block;">
                                </div>
                            @endif
                        @endforeach
                    </div>

                </tr>
            </thead>
        </table>
    </section>
    <!--<div class="page_break"></div>-->
    <section class style="margin-top:10px; margin-bottom:10px">
        <table style="width:100%;">
            <thead>
                <tr>
                    <td>
                        <h4 class="sctionMdTitle text-primary">{{ $property->project->mainCommunity->name }}</h4>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="text-secondary">{{ $property->project->mainCommunity->shortDescription }}</p></br>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="display: inline-block;">

                            @foreach ($property->project->mainCommunity->imageGallery as $key => $image)
                                @if ($key < 3)
                                    <div class="vtTextBXox" style="padding:2px; display: inline-block;">
                                        <img src="{{ $image['path'] }}" height="100" style="display: inline-block;">
                                    </div>
                                @endif
                            @endforeach
                        </div>

                    </td>
                </tr>
            </thead>
        </table>
    </section>
    <!--<div class="page_break"></div>-->
    <section>
        <table style="width:100%;">
            <tr>
                <td style="width:70%">
                    {!! $property->project->developer->short_description !!}
                </td>
                <td style="width:30%">
                    <img src="{{ $property->project->developer->logo }}" width="200">
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
