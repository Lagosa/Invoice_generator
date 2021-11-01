<?php
  echo "<div style='font-size: 30px; font-weight: 600;text-align:center;margin-top:150px;'> Generare factură...</div>";


  require_once 'mpdf/vendor/autoload.php';

  $pdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-P']);
  $pdf -> addPage();
  $pdf -> setFont('Arial','B','20');

  // if(isset($_POST['buyerName']))
  // {
    $buyerName = $_POST['existingFirmName'];
    $buyerCIF = $_POST['existingFirmCIF'];
    $buyerBank = $_POST['existingFirmBank'];
    $buyerNrOrd= $_POST['existingFirmNrOrd'];
    $buyerCounty = $_POST['existingFirmCounty'];
    $buyerOffice = $_POST['existingFirmOffice'];
    $buyerBankAccount = $_POST['existingFirmBankAccount'];
  // }

  if($buyerName == "")
  {
    $buyerName = $_POST["newFirmName"];
    $buyerCIF = $_POST['newFirmCIF'];
    $buyerBank = $_POST['newFirmBank'];
    $buyerNrOrd= $_POST['newFirmNrOrd'];
    $buyerCounty = $_POST['newFirmCounty'];
    $buyerOffice = $_POST['newFirmOffice'];
    $buyerBankAccount = $_POST['newFirmBankAccount'];
    saveNewFirm($buyerName,$buyerNrOrd,$buyerCIF,$buyerOffice,$buyerCounty,$buyerBankAccount,$buyerBank);
  }

  $numberOfProducts = $_POST['numberOfProducts'];
  $productList = "";
  for($i = 1; $i <= $numberOfProducts; $i++)
  {
    $productList .= "<tr> <td style='text-align: center;'> ".$i." </td> <td > ".$_POST['productName'.$i]." </td> <td style='text-align: center;'> ".$_POST['measurementUnit'.$i]." </td> <td style='text-align:center;'> ".$_POST['quantity'.$i]." </td>
                          <td style='text-align:center;'> ".$_POST['unitPrice'.$i]." </td> <td style='text-align:center;'> ". round($_POST['totalPrice'.$i],2)." </td> <td style='text-align:center;'> ".round($_POST['priceWithVAT'.$i],2)." </td></tr>";
  }
  for($i = $numberOfProducts + 1; $i <= 30;$i++)
  {
    $productList .= "<tr > <td color='white'> - </td> </tr>";
  }

  $page = "
            <style>
              .page{
                font-family: arial;
                font-size: 11px;
              }
              table{
                font-family: arial;
                font-size: 11px;
              }
              .header{

                border: 1px solid black;
                padding: 10px;
                width: 100%;
              }
              .firmName{
                font-weight: bold;
                font-size: 11px;
                text-align: center;
              }
              .label{
                text-align: right;
              }
              .title{
                text-align: center;
                font-size: 17px;
                font-weight: bolder;
              }
              tr{
                background-color: ;
              }
              .productTable{

              }
              .contentRow{
                border-left: 0px solid black;
                padding-left: 10px;
                padding-right: 10px;
              }
              .contentHeaderRow{
                text-align: center;
                font-weight: bold;
                border-bottom: 1px solid black;
              }
              .footerTable{
                border: 1px solid black;
              }
              .borderLeft {
                border-left: 1px solid black;
              }
              .borderRight{
                border-right: 1px solid black;
              }
              .borderBottom{
                border-bottom: 1px solid black;
              }

            </style>
            <div class='page'>
              <table class='header'>
                <tr>
                  <td colspan='2' class='firmName'> ".$_POST['pName'  ]." </td> <td>  </td> <td>  </td> <td class='label'> Cumpărător: </td> <td class='firmName' style='text-align: left;'> ".$buyerName."</td>
                </tr>
                <tr>
                  <td class='label'> Nr.ord.RC/an: </td> <td  style='width: 30%;'> ".$_POST['pNrOrdRc']." </td> <td> </td> <td> </td> <td class='label'> Nr.ord.RC/an: </td> <td> ".$buyerNrOrd." </td>
                </tr>
                <tr>
                  <td class='label'> C.I.F.: </td> <td> ".$_POST['pCIF']." </td> <td> </td> <td> </td> <td class='label'> C.I.F.: </td> <td> ".$buyerCIF." </td>
                </tr>
                <tr>
                  <td class='label'> Sediu: </td> <td> ".$_POST['pOffice']." </td> <td class='title' colspan='2'> FACTURĂ </td><td class='label'> Sediu: </td> <td> ".$buyerOffice." </td>
                </tr>
                <tr>
                  <td class='label'> Județul: </td> <td> ".$_POST['pCounty']." </td> <td >  </td> <td > </td><td class='label'> Județul: </td> <td> ".$buyerCounty." </td>
                </tr>
                <tr>
                  <td class='label'> Cont: </td> <td> ".$_POST['pBankAccount1']." </td> <td class='label'> Data: </td> <td style='font-weight: bold;'> ".$_POST['invoiceDate']." </td><td class='label'> Cont: </td> <td> ".$buyerBankAccount." </td>
                </tr>
                <tr>
                  <td class='label'> Banca: </td> <td> ".$_POST['pBank1']." </td> <td class='label'> Seria: </td> <td style='font-weight: bold;'>".$_POST['serialNumber']." </td> <td class='label'> Banca: </td> <td> ".$buyerBank." </td>
                </tr>
                <tr>
                  <td class='label'> Cont: </td> <td> ".$_POST['pBankAccount2']." </td> <td> </td> <td> </td> <td></td> <td></td>
                </tr>
                <tr>
                  <td class='label'> Banca: </td> <td> ".$_POST['pBank2']." </td> <td> </td> <td> </td> <td></td> <td></td>
                </tr>
                </table>
                <br>
                <div> Cota TVA: 19% </div>
                <br>
                <table class='productTable'>
                  <tr>
                    <td class='contentRow contentHeaderRow' style='border-left: none;'> Nr. <br> crt. </td> <td class='contentRow contentHeaderRow'> Denumirea produselor sau a serviciilor </td> <td class='contentRow contentHeaderRow'> U.M. </td> <td class='contentRow contentHeaderRow'> Cantitatea </td> <td class='contentRow contentHeaderRow'> Preț unitar (fără T.V.A.) <br> -ron- </td> <td class='contentRow contentHeaderRow'> Valoare <br> -ron- </td> <td class='contentRow contentHeaderRow'> Valoare T.V.A. <br> -ron- </td>
                  </tr>
                  ".$productList."
                </table>
                <table class='footerTable'>
                  <tr>
                    <td  > Semnătura și ștampila <br> furnizorului </td>
                    <td rowspan='3' class='borderLeft borderRight'>
                      Date privind expediția: <br>
                      Numele delegatului: <em>".$_POST['delegateName']."</em> <br>
                      B.I./C.I. seria <em>".$_POST['delegateIdSeries']."</em> nr. <em>".$_POST['delegateIdNumber']." </em> eliberat(ă) de <em>".$_POST['delegateIdIssued']."</em> <br>
                      Mijlocul de transport <em>".$_POST['delegateVehicle']."</em> nr. <em>".$_POST['delegateVehicleLicensePlateNr']."</em> <br>
                      Expedierea s-a făcut în prezența noastră la <br> data de <em>".$_POST['delegateDate']."</em> ora <em>".$_POST['delegateHour']."</em> <br>
                      Semnăturile: <br><br>
                    </td>
                    <td class='borderRight borderBottom' style='width: 120px;text-align:center;'>
                      Total din care:
                    </td>
                    <td class='borderRight borderBottom' style='width: 70px; text-align:center;'>
                      <b>".round($_POST['totalWithoutVAT'],2)."</b>
                    </td>
                    <td class='borderBottom' style='width: 90px;text-align: center;'>
                      <b>".round($_POST['totalVAT'],2)."</b>
                    </td>
                  </tr>
                  <tr>
                    <td rowspan='2'> </td>
                    <td class='borderRight'> Semnăturile <br>de primire:<br><br> </td>
                      <td colspan='2' rowspan='2' style='text-align:center;'> Total de plată:<br>  <b><h3>".round($_POST['total'],2)."</h3></b> </td>
                  </tr>
                  <tr><td class='borderRight' style='height: 30px;'></td> </tr>
                </table>
              </div>";
  $pdf -> WriteHTML($page);
  $pdf -> Output("Factura_".$buyerName."_".$_POST['invoiceDate'].".pdf",'D');

  function saveNewFirm($name,$nrOrd,$CIF,$office,$county,$bankAccount,$bank)
  {
    require "connectToDatabase.php";

    $sql = "INSERT INTO firm_data (NAME,NR_ORD,CIF,OFFICE,COUNTY,BANK_ACCOUNT,BANK) VALUES ('".$name."','".$nrOrd."','".$CIF."','".$office."','".$county."','".$bankAccount."','".$bank."')";
    mysqli_query($conn,$sql);

  }
 ?>
