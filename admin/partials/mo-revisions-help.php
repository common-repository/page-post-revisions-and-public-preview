
<head>
  
<style>
#mo-logo{
  height: 60px;
  width: 250px;
}
.mo-rev-top{
  text-align: center;
}
.mo-rev-top h1{
  color: #5f6062;
}
#mo-table-heading{
  text-align: center;
}


.mo-help-ul {
  counter-reset: index;  
  padding: 0;
  font-size : 16px !important;
 
}

.mo-help-li {
  counter-increment: index; 
  display: flex;
  align-items: center;
  padding: 12px 0;
  box-sizing: border-box;
}


.mo-help-li::before {
  content: counters(index, ".", decimal-leading-zero);
  font-size: 1.5rem;
  text-align: right;
  font-weight: bold;
  min-width: 50px;
  padding-right: 12px;
  font-variant-numeric: tabular-nums;
  align-self: flex-start;
  background-image: linear-gradient(to bottom, aquamarine, orangered);
  background-attachment: fixed;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}


.mo-help-li + .mo-help-li {
  border-top: 1px solid rgb(132 233 55 / 20%);
}
.my-list{
    text-align: -webkit-center;
}
</style>
</head>

<div class='mo-rev-top'>
  <img id="mo-logo" src = 'https://www.miniorange.com/images/logo/miniorange-logo.webp' >
  <h1>Revisions Manager</h1>
</div>
<br>
<h2>How miniOrange Revision Manager works -</h2>
<div class="my-list">
<ul class="mo-help-ul">
  <li class="mo-help-li">User creates some changes on some page/post and clicks &nbsp;<strong> update </strong>&nbsp; button.</li>
  <li class="mo-help-li">The changes created on that page/post don't reflect directly on the live site.</li>
  <li class="mo-help-li">Those changes are sent for review to the reviewer/admin.</li>
  <li class="mo-help-li">Reviewer/admin is informed by mail that contains details about the change request.</li>
  <li class="mo-help-li">And the reviewer/admin can also see a list of all change requests of different pages on miniOrange Revision manager dashboard.</li>
  <li class="mo-help-li">On miniOrange Revision Manager dashboard, Every change request has a view button.</li>
  <li class="mo-help-li">Whenever reviewer/admin clicks that view button he/she is redirected to the detailed view page of that change request.</li>
  <li class="mo-help-li">On that detailed view page admin can view Code Difference and UI Difference.</li>
  <li class="mo-help-li">After reviewing code and ui reviewer/admin can approve or deny those changes.</li>
  <li class="mo-help-li">When reviewer/admin clicks on &nbsp;<b> Approve </b>&nbsp; button then the changes will be applied on live page.</li>
  <li class="mo-help-li">And the user who created changes will be informed by mail.</li>
  <li class="mo-help-li">When reviewer/admin clicks on &nbsp;<b> Deny </b>&nbsp; button then the changes will be rejected and the user will be informed by mail.</li>
</ul>
</div>