<?php
/**
 * Template Name: Evaluation-Tracker
 */
?>
<!DOCTYPE html>


<html>

<head>
    <link href="https://helpdeskforhr.com/wp-content/themes/thrive-theme-child/perf-eval-new/assets/css/tabs.css" rel="stylesheet"/>
        <link href="https://helpdeskforhr.com/wp-content/themes/thrive-theme-child/perf-eval-new/assets/css/style.css" rel="stylesheet"/>
        <link href="https://helpdeskforhr.com/wp-content/themes/thrive-theme-child/perf-eval-new/assets/css/cards.css" rel="stylesheet"/>
        <link href="https://helpdeskforhr.com/wp-content/themes/thrive-theme-child/perf-eval-new/assets/css/upload-form.css" rel="stylesheet"/>
        <link href="../assets/css/icons/icons.css" rel="stylesheet"/>
        <link href="https://helpdeskforhr.com/wp-content/themes/thrive-theme-child/perf-eval-new/assets/css/footer.css" rel="stylesheet"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">    </head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/docxtemplater/3.28.6/docxtemplater.js"></script>
        <script src="https://unpkg.com/pizzip@3.1.1/dist/pizzip.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.js"></script>
        <script src="https://unpkg.com/pizzip@3.1.1/dist/pizzip-utils.js"></script>
        
<script src='https://helpdeskforhr.com/wp-content/themes/thrive-theme-child/perf-eval-new/assets/js/data/createDocument.js'></script>

</head>

<script>
  function selectElement(id, valueToSelect) {    
    let element = document.getElementById(id);
    element.value = valueToSelect;
}
    let evaluationResponses;
   
let reviewList;
let select = document.getElementById('reviewOptions');
var url = new URL(window.location.href);
var initialRef = url.searchParams.get("c");
console.log(initialRef)
if(initialRef != null){
let rsp = $.get(`https://pe-apis.herokuapp.com/find-evaluation?refno=${initialRef}`, (data)=>{
    evaluationResponses = data
    })
  }
let str = ''

window.onload = ()=>{
  setTimeout(()=>{


  console.log(evaluationResponses)
if (localStorage.getItem('review-list')){

  reviewList = JSON.parse(localStorage.getItem('review-list'))
console.log(reviewList)
let refArr = [];

reviewList.forEach(i=>{
  refArr.push(i.refNo)
})


if(initialRef != null){
if(!refArr.includes(initialRef)){
  reviewList.push({
   refNo:  evaluationResponses[0].referenceNo,
   name: evaluationResponses[0].employeeName
  })
  localStorage.setItem('review-list', JSON.stringify(reviewList))

  reviewList.forEach(i=>{

str += `<option value='${i.refNo}'>${i.name}</option>`
})
console.log(str)
document.getElementById('reviewOptions').innerHTML = str;
document.getElementById('reviewOptions').style.display = 'block';
}}else {
  if(initialRef == null){
  setTimeout(()=>{
    let valueofselect = document.getElementById('reviewOptions').value
  console.log(valueofselect)
  setEval(valueofselect)
 
  },1000)
  }

}

  reviewList.forEach(i=>{

    str += `<option value='${i.refNo}'>${i.name}</option>`
  })
  console.log(str)
  document.getElementById('reviewOptions').innerHTML = str;
  document.getElementById('reviewOptions').style.display = 'block';


  reviewList.forEach(i=>{
    //HERE
let ref;
    let rsp2 = $.get(`https://pe-apis.herokuapp.com/find-evaluation?refno=${i.refNo}`, (data)=>{
  if(data){
    ref = data
  } else{
    ref = null
  }
     
 
    })
    setTimeout(()=>{


console.log(ref)
    if(ref.length == 0){
     reviewList = reviewList.filter(function( obj ) {
  return obj.refNo !== i.refNo;


});
$(`#reviewOptions option[value='${i.refNo}']`).remove();
    }
    localStorage.setItem('review-list', JSON.stringify(reviewList))
  },1000)
  })
} else{
  if(evaluationResponses != null){
  let arr = []
  arr.push({
   refNo:  evaluationResponses[0].referenceNo,
   name: evaluationResponses[0].employeeName
  })
  localStorage.setItem('review-list', JSON.stringify(arr))
}
}
},1000)
}


setTimeout(()=>{
  if(evaluationResponses != null){
  evaluationResponses[0].Responses.map(i=>{
    let status;
    let color;
    let enable;
    if(i.isComplete == true){
      status = 'Complete'
      color = 'green'
      enable = false
    } else {
      status = 'Pending'
      color = 'tomato'
      enable = true
    }
    if (i.date_sign == ''){
      i.date_sign = 'NA'
    }
    document.getElementById('itemTable').innerHTML += `
    <tr>
      <td>${i.referenceNumber}</td>
      <td>${i.responderName}</td>
      <td style='color:${color}'>${status}</td>
      <td>${i.date_sign}</td>
      <td><span title="Download Document"  class='icon-tracker icon--document' disabled=${enable}></span>&nbsp;&nbsp;<span title="Reset Evaluation" class='icon-tracker icon--reset' disabled=${enable}></span>&nbsp;&nbsp;<span title="Resend Email To Reviewer" class='icon-tracker icon--send--email' disabled=${enable}></span></td>
      </tr>`
  })
  document.getElementById('personReviewed').innerHTML = evaluationResponses[0].employeeName
  selectElement('reviewOptions', initialRef)
} else {
  
}
selectElement('reviewOptions', initialRef)
},1000)




const changeEval = (event)=>{
let value = event.target.value;
console.log(value)
var newRef;
document.getElementById('itemTable').innerHTML = ``;
let rsp2 = $.get(`https://pe-apis.herokuapp.com/find-evaluation?refno=${value}`, (data)=>{
  newRef = data
  console.log(data)
    })
  setTimeout(()=>{
    console.log(newRef)
    newRef[0].Responses.map(i=>{
    let status;
    let color;
    let enable;
    if(i.isComplete == true){
      status = 'Complete'
      color = 'green'
      enable = false
    } else {
      status = 'Pending'
      color = 'tomato'
      enable = true
    }
    if (i.date_sign == ''){
      i.date_sign = 'NA'
    }
  
    document.getElementById('itemTable').innerHTML += `
    <tr>
      <td>${i.referenceNumber}</td>
      <td>${i.responderName}</td>
      <td style='color:${color}'>${status}</td>
      <td>${i.date_sign}</td>
      <td><span title="Download Document"  class='icon-tracker icon--document' disabled=${enable}></span>&nbsp;&nbsp;<span title="Reset Evaluation" class='icon-tracker icon--reset' disabled=${enable}></span>&nbsp;&nbsp;<span title="Resend Email To Reviewer" class='icon-tracker icon--send--email' disabled=${enable}></span></td>
      </tr>`
  })
  document.getElementById('personReviewed').innerHTML = newRef[0].employeeName
  
},1000)

}


const setEval = (a)=>{
let value = a;
console.log(value)
var newRef;
document.getElementById('itemTable').innerHTML = ``;
let rsp2 = $.get(`http://localhost:3004/find-evaluation?refno=${value}`, (data)=>{
  newRef = data
  console.log(data)
    })
  setTimeout(()=>{
    console.log(newRef)
    newRef[0].Responses.map(i=>{
    let status;
    let color;
    let enable;
    if(i.isComplete == true){
      status = 'Complete'
      color = 'green'
      enable = false
    } else {
      status = 'Pending'
      color = 'tomato'
      enable = true
    }
    if (i.date_sign == ''){
      i.date_sign = 'NA'
    }
  
    document.getElementById('itemTable').innerHTML += `
    <tr>
      <td>${i.referenceNumber}</td>
      <td>${i.responderName}</td>
      <td style='color:${color}'>${status}</td>
      <td>${i.date_sign}</td>
      <td><span title="Download Document"  class='icon-tracker icon--document' disabled=${enable}></span>&nbsp;&nbsp;<span title="Reset Evaluation" class='icon-tracker icon--reset' disabled=${enable}></span>&nbsp;&nbsp;<span title="Resend Email To Reviewer" class='icon-tracker icon--send--email' disabled=${enable}></span></td>
      </tr>`
  })
  document.getElementById('personReviewed').innerHTML = newRef[0].employeeName
},1000)

}
  
</script>

<body>
     <!--Top Bar-->
  <div class="topnav">
    <img src='https://helpdeskforhr.com/wp-content/themes/thrive-theme-child/perf-eval-new/assets/css/img/PECLogo.png' style="padding: 10px; margin-left: 20px;" width="450px" height="auto"/>
     
   </div>

   <div style="padding: 50px;">
    <div style="margin-bottom: 25px;" class="form-group row">
      <select onchange="changeEval(event)" id="reviewOptions"></select>
      <div class="col-md-6">
          <label for="inputReviewingSupervisor"><b>Person Being Reviewed:</b> <span id="personReviewed"></span></label>
      </div>
     
  </div>
   <div style="background-color: white; padding: 20px;">
  
   <table class="table">
    <thead>
      <tr>
       
        <th scope="col">Reference No.</th>
        <th scope="col">Reviewer</th>
        <th scope="col">Status</th>
        <th scope="col">Date Completed</th>
        <th scope="col">Document</th>
      </tr>
    </thead>
    <tbody id="itemTable">
     
    </tbody>
  </table>
  </div>
  </div>
<!---->
</body>
</html>