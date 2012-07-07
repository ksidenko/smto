<?php $this->layout = 'application.views.layouts.main'; ?>
<h2>PDF</h2>

<?php echo CHtml::link(
    'Render & Direct Download',
    array('/p2/ajax/renderPdf','download' => true)
); ?>

<?php echo CHtml::ajaxLink(
'Publish & Link',
array('/p2/ajax/renderPdf', 'link' => true),
array(
'beforeSend' => 'function(){$("#result").css("background","#ccc")}',
'success' => 'function(data){$("#result").css("background","#eee").html(data)}',
    'error' => 'function(request, data){$("#result").css("background","#eee").html(data)}',
)
); ?>

<?php echo CHtml::ajaxLink(
'Publish',
array('/p2/ajax/renderPdf', 'publish' => true),
array(
'beforeSend' => 'function(){$("#result").css("background","#ccc")}',
'success' => 'function(data){$("#result").css("background","#eee").html(data)}',
    'error' => 'function(request, data){$("#result").css("background","#eee").html(data)}',
)
); ?>

<?php echo CHtml::ajaxLink(
'Flash Preview',
array('/p2/ajax/renderPdf', 'preview' => true),
array(
'beforeSend' => 'function(){$("#result").css("background","#ccc").html("rendering ...")}',
'success' => 'function(data){$("#result").css("background","#eee").html(data)}',
    'error' => 'function(request, data){$("#result").css("background","#eee").html(data)}',

)
); ?>

<div id="result" style="border: 1px solid #333; height: 700px; padding: 1em">
    NOTE! This is a debug page for rendering PDFs from P2Html contents with lithron and PDFlib.<br/>
    In preview mode there also SWF files rendered with pdf2swf (swftools.org).<br/><br/>
    You may change the PDF template in <i>modules/p2/views/pdf/pdf-template.php</i>
</div>