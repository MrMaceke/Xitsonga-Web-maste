<?php

?>
<!-- Modal -->
<div class="modal hide addUploadContractDialog" id="addUploadContractDialog" tabindex="-5" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Contract</h4>
            </div>
            <div class="modal-body form-horizontal row-fluid">
                <input id = "projectId" type ="hidden" value="{{project.projectId}}"/>
                <input id = "clientId" type ="hidden" value="{{project.clientId}}"/>
                <div class="control-group">
                    <label class="col-sm-4 control-label text-right">Project ID</label>
                    <div class="controls">
                        <span id = "projectId"  class ='form-control text-left' style ="background: none;border:none;">{{project.projectId}}</span>
                    </div>
                </div>
                <div class="control-group">
                    <label class="col-sm-4 control-label"></label>
                    <form method="post" id="contractfileinfo" name="contractfileinfo" enctype="multipart/form-data" >
                       <div class="controls text-right" style ="margin-top: 5px">
                           <input type="file" name ="file"/>
                       </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="uploadProjectContractDocument(event)">Upload Contract</button>
            </div>
        </div>
    </div>
</div>
