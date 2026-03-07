<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Violation Notice</title>

<style>

body{
    font-family: "Times New Roman", Times, serif;
    font-size: 13px;
    line-height: 1.6;
    color:#000;
}

.container{
    width:100%;
    margin:auto;
}

.header{
    text-align:center;
    border-bottom:2px solid #000;
    padding-bottom:10px;
    margin-bottom:25px;
}

.header h2{
    margin:0;
    font-size:18px;
    letter-spacing:2px;
}

.meta{
    width:100%;
    margin-bottom:30px;
}

.meta td{
    vertical-align:top;
}

.subject{
    text-align:center;
    font-weight:bold;
    text-transform:uppercase;
    margin-bottom:25px;
}

.section{
    margin-bottom:20px;
}

.violation-box{
    border-left:4px solid #b91c1c;
    padding:10px;
    background:#f9fafb;
}

.instructions{
    border:1px solid #ddd;
    padding:10px;
    background:#f0f9ff;
}

.signature{
    margin-top:60px;
}

.small{
    font-size:11px;
    color:#555;
}

</style>

</head>

<body>

<div class="container">

@if($letterData && is_array($letterData))

    {{-- Header --}}
    <div class="header">
        <h2>{{ $letterData['letter_head'] ?? 'NAIROBI CITY COUNTY GOVERNMENT' }}</h2>
        <div>Market Enforcement Department</div>
    </div>


    {{-- Meta Information --}}
    <table class="meta">
        <tr>
            <td>
                <strong>Reference:</strong> {{ $letterData['reference_number'] ?? '' }}<br>
                <strong>Date:</strong> {{ $letterData['date_of_observation'] ?? '' }}
            </td>

            <td align="right">
                <strong>TO:</strong> {{ $letterData['recipient_name'] ?? '' }}<br>
                <strong>STALL:</strong> {{ $letterData['stall_number'] ?? '' }}
            </td>
        </tr>
    </table>


    {{-- Subject --}}
    <div class="subject">
        {{ $letterData['subject'] ?? 'Official Notice of Violation' }}
    </div>


    {{-- Opening --}}
    <div class="section">
        {{ $letterData['opening_statement'] ?? '' }}
    </div>


    {{-- Violation Details --}}
    <div class="section violation-box">

        <strong>Nature of Infringement</strong><br><br>

        <strong>Observation:</strong><br>
        {!! nl2br(e($letterData['violation_details'] ?? '')) !!}

        @if(!empty($letterData['law_reference']))
        <br><br>
        <strong>Statutory Basis:</strong>
        <em>{{ $letterData['law_reference'] }}</em>
        @endif

        @if(!empty($letterData['community_impact']))
        <br><br>
        <strong>Public Impact:</strong>
        {{ $letterData['community_impact'] }}
        @endif

    </div>


    {{-- Consequences --}}
    <div class="section">

        <strong>Legal Consequences</strong><br><br>

        {!! nl2br(e($letterData['legal_consequences'] ?? '')) !!}

        <br><br>

        <strong>Compliance Deadline:</strong>
        {{ $letterData['compliance_deadline'] ?? 'Immediate' }}

    </div>


    {{-- Instructions --}}
    <div class="section instructions">

        <strong>Required Remedial Action</strong><br><br>

        {!! nl2br(e($letterData['instructions'] ?? '')) !!}

    </div>


    {{-- Appeal --}}
    @if(!empty($letterData['appeal_rights']))
    <div class="section small">
        <strong>Right of Reply:</strong>
        {{ $letterData['appeal_rights'] }}
    </div>
    @endif


    {{-- Signature --}}
    <div class="signature">

        Sincerely,<br><br>

        {!! nl2br(e($letterData['signature_block'] ?? 'Authorized Officer')) !!}

        <br><br>

        <div class="small">
            Official Digital Signature: {{ $letterData['case_id'] ?? 'VERIFIED' }}
        </div>

    </div>

@else

    {{ $violation->ai_raw_message ?? 'No content available' }}

@endif

</div>

</body>
</html>