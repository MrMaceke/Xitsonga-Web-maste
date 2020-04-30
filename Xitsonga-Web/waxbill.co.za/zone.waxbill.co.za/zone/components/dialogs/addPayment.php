<?php

?>
<div class ='addPaymentDialog' style ="visibility: hidden">
    <br/><br/>
    <div class="module" style ="background: #F8F5F0">
        <div class="module-head"><h3>Add Payment</h3></div>
        <div class="module-body">
            <div class ='error'></div>
            <input  id = "clientId" type="hidden" class="form-control span8" value ="{{client.client.userID}}"/>
            <form method="get" class="form-horizontal row-fluid">
                <div class="control-group">
                    <label class="control-label">Project Id</label>
                    <div class="controls">
                       <input  id = "projectId" type="text" class="form-control span8" placeholder="Project Id"/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Reference</label>
                    <div class="controls">
                       <input  id = "paymentReference" type="text" class="form-control span8" placeholder="Payment Reference"/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Payment Amount</label>
                    <div class="controls">
                        <input  id = "paymentAmount" type="number" class="form-control span8" placeholder="Payment Amount"/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Payment Date</label>
                    <div class="controls">
                        <input  id = "paymentDate" type="text" class="form-control span8 datePicker" placeholder="Payment Date"/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"></label>
                    <div class="controlso">
                        <button class="btn btn-danger" type="submit" onclick="reloadPage(event)">Cancel</button>
                        <button class="btn btn-primary" type="submit" onclick="addNewPayment(event)">Add Payment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>