<?php
/**
 * Template Name: Perf-eval-download-doc .000000001
 */
?>

<!DOCTYPE html>


<html>
    <head>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/docxtemplater/3.28.6/docxtemplater.js"></script>

        <script src="https://unpkg.com/pizzip@3.1.1/dist/pizzip.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.js"></script>
        <script src="https://unpkg.com/pizzip@3.1.1/dist/pizzip-utils.js"></script>
        
    </head>
<script>
     let anObjArr = {
        emp_Name:'',
        employee_id_no :'',
        job_title: '',
        department: '',
        rev_sup:'',
        rev_period: '',
        per_sup:'',
        t_o_p: '',
        part_two_a: '',
        overall_rating: '',
        part_4_a:'',
        part_4_b:'',
        categories: '',
        signature: '',
        date_sign: ''
        
       
    }
       var url = new URL(window.location.href);
        var initialRef = url.searchParams.get("c");
        var responseRef = url.searchParams.get("d");
        console.log(responseRef)
        let rsp = $.get(`https://pe-apis.herokuapp.com/find-evaluation?refno=${initialRef}`, (data)=>{
         console.log( data[0].Responses)
       let response = data[0].Responses.filter(i=> i.referenceNumber == responseRef)
       console.log(response)
       anObjArr = {
        emp_Name:response[0].emp_Name,
        employee_id_no :response[0].employee_id_no,
        job_title: response[0].job_title,
        department: response[0].department,
        rev_sup:response[0].rev_sup,
        rev_period: response[0].rev_period,
        per_sup:response[0].per_sup,
        t_o_p: response[0].t_o_p,
        part_two_a: response[0].part_two_a,
        overall_rating: response[0].overall_rating,
        part_4_a:response[0].part_4_a,
        part_4_b:response[0].part_4_b,
        categories: response[0].categories,
        signature: response[0].signature,
        date_sign: response[0].date_sign
        
       
    }


    generate()

    return closeWindow()
    });    
      

   
   
    window.onload = ()=>{
      

    }

    let closeWindow = ()=>{
      setTimeout(()=>{
        window.close()
      },5000)
       
    }
</script>
    <body>

    </body>

    <script >
        function loadFile(url, callback) {
    PizZipUtils.getBinaryContent(url, callback);
}
function generate() {
    loadFile(
        "https://helpdeskforhr.com/wp-content/themes/thrive-theme-child/perf-eval-new/example.docx",
        function (error, content) {
            if (error) {
                throw error;
            }
            var zip = new PizZip(content);
            var doc = new window.docxtemplater(zip, {
                paragraphLoop: true,
                linebreaks: true,
            });

            // Render the document (Replace {first_name} by John, {last_name} by Doe, ...)
            doc.render(anObjArr);

            var out = doc.getZip().generate({
                type: "blob",
                mimeType:
                    "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                // compression: DEFLATE adds a compression step.
                // For a 50MB output document, expect 500ms additional CPU time
                compression: "DEFLATE",
            });
            // Output the document using Data-URI
            saveAs(out, "output.docx");
        }
    );
}






    

        
        </script>
</html>