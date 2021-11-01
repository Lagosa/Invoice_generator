<?php

    session_start();
    if(!isset($_SESSION['loggedin']))
    {
      header("Location: loginPage.html");
    }

    include "connectToDatabase.php";
    include 'loadData.php';

    $q = "SELECT NAME FROM firm_data ORDER BY NAME ASC";
    $query = mysqli_query($conn,$q);

    $visibilityListExistingFirm = "visible";
    $visibilityInputExistingFirm = "hidden";
    $visibilityInputNewFirm = "hidden";
    $valueInputNewFirm = "";
    $valueInputExistingFirm = "";
    if(isset($_GET['firmName']))
    {
      $visibilityListExistingFirm = "hidden";
      if($_GET['firmName'] != "newFirm")
      {
        $visibilityInputExistingFirm = "visible";
        $valueInputExistingFirm = "value = '".$firmName."'";
      }else {
        $visibilityInputNewFirm = "visible";
      }
    }
?>
<html lang="ro" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Generator facturi</title>

    <script>
        let productArray = [["Balastru",36],
                            ["Nisip 0-4",43],
                            ["Piatră 4-8",41],
                            ["Piatră 8-16",40],
                            ["Piatră 16-31.5",37],
                            ["Prestări servicii de transport",0]];

        function loadData()
        {
          var auxFormInput = document.getElementById("firmNameH");
          var firmNameOption = document.getElementById("firmName");
          auxFormInput.value = firmNameOption.value;
          var auxForm = document.getElementById("auxiliarForm");
          auxForm.submit();
          firmNameOption.visibility = hidden;

        }

        function getPrice(rowID)
        {
          var product = document.getElementById('productName' +  rowID).value;
          for(let i = 0;i < 6; i++)
          {
            if(product == productArray[i][0])
            {
              document.getElementById('unitPrice' + rowID).value = productArray[i][1];
              return;
            }
          }
        }

        function computeTotal()
        {
          var totalVAT = document.getElementById('totalVAT');
          var total = document.getElementById('total');

          var valueTotalVAT = 0;
          var valueTotalWithoutVAT = 0;
          for(let i = 1; i<numberOfLastRow;i++)
          {
            valueTotalWithoutVAT += parseFloat(document.getElementById('totalPrice' + i).value,10);
             valueTotalVAT += parseFloat(document.getElementById('priceWithVAT' + i).value,10);
          }

          totalVAT.value = valueTotalVAT;
          total.value = valueTotalVAT + valueTotalWithoutVAT;
        }

        function getVAT(rowID)
        {
          var totalPrice = document.getElementById( 'totalPrice' + rowID).value;
          document.getElementById('priceWithVAT' + rowID).value = (totalPrice * 19)/100;

          computeTotal();

          return;
        }

        function getTotalPrice(rowID)
        {
          var quantity = document.getElementById('quantity' + rowID).value;
          var unitPrice = document.getElementById('unitPrice' + rowID).value;
          document.getElementById('totalPrice' + rowID).value = quantity * unitPrice;
        }

        var numberOfLastRow = 1;
        var numberOfRows = 0;

        function addRow()
        {
          numberOfRows = numberOfRows + 1;
          var table = document.getElementById("contentTable");

          var row = table.insertRow(numberOfLastRow);

          var cell = row.insertCell(0);
          cell.innerHTML = numberOfLastRow;


          cell = row.insertCell(1);
          var formInput = document.createElement('select');
          formInput.setAttribute('id','productName'+numberOfRows);
          formInput.setAttribute('name','productName'+numberOfRows);
          formInput.setAttribute('onchange','getPrice('+numberOfRows+')');
          var option = document.createElement('option');
          option.value = "-";
          option.innerHTML = "Selectează produsul";
          formInput.appendChild(option);
          for(let i = 0; i < 6; i++)
          {
            option = document.createElement('option');
            option.value = productArray[i][0];
            option.innerHTML = productArray[i][0];
            formInput.appendChild(option);
          }
          cell.appendChild(formInput);


          cell = row.insertCell(2);
          formInput = document.createElement('select');
          option = document.createElement('option');
          option.value = 'mc';
          option.innerHTML = 'mc';
          formInput.appendChild(option);
          option = document.createElement('option');
          option.value = ' ';
          option.innerHTML = ' ';
          formInput.appendChild(option);
          formInput.setAttribute('name','measurementUnit'+numberOfRows);
          formInput.setAttribute('id','measurementUnit'+numberOfRows);
          cell.appendChild(formInput);


          cell = row.insertCell(3);
          formInput = document.createElement('input');
          formInput.setAttribute('type','number');
          formInput.setAttribute('id','quantity'+numberOfRows);
          formInput.setAttribute('name','quantity'+numberOfRows);
          formInput.setAttribute('onChange','getTotalPrice('+numberOfRows+'), getVAT('+numberOfRows+')');
          cell.appendChild(formInput);


          cell = row.insertCell(4);
          formInput = document.createElement('input');
          formInput.setAttribute('type','text');
          formInput.setAttribute('id','unitPrice' + numberOfRows);
          formInput.setAttribute('name','unitPrice' + numberOfRows);
          formInput.setAttribute('onChange','getTotalPrice('+numberOfRows+'), getVAT('+numberOfRows+')');
          cell.appendChild(formInput);


          cell = row.insertCell(5);
          formInput = document.createElement('input');
          formInput.setAttribute('type','text');
          formInput.setAttribute('name','totalPrice'+numberOfRows);
          formInput.setAttribute('id','totalPrice' + numberOfRows);
          formInput.readOnly = true;
          cell.appendChild(formInput);


          cell = row.insertCell(6);
          formInput = document.createElement('input');
          formInput.setAttribute('type','text');
          formInput.readOnly = true;
          formInput.setAttribute('id','priceWithVAT' + numberOfRows);
          formInput.setAttribute('name','priceWithVAT' + numberOfRows);
          cell.appendChild(formInput);

          formInput = document.getElementById('numberOfProducts');
          formInput.value = numberOfLastRow;

          numberOfLastRow = numberOfLastRow + 1;

        }

        function removeRow()
        {
          var table = document.getElementById("contentTable");
          if(numberOfLastRow > 2)
          {
            numberOfLastRow -= 1;
            numberOfRows -= 1;

            var row = table.deleteRow(numberOfLastRow);
            var formInput = document.getElementById('numberOfProducts');
            formInput.value = numberOfRows;
          }
        }

        function clickedEffect(id)
        {
          var btn = document.getElementById("btn"+id);
          if(id == "1")
          {
            btn.setAttribute("class","commandButtonClicked cbleft");
            setTimeout('',5000);
            btn.setAttribute("class","commandButton cbleft");
          }else {
            btn.setAttribute("class","commandButtonClicked cbright");
            setTimeout('',5000);
            btn.setAttribute("class","commandButton cbright");
          }
        }

        function computeTotalWithoutVAT()
        {
          totalWithoutVAT = 0;
          for(let i = 1;i < numberOfLastRow-1; i++)
          {
            totalWithoutVAT += parseFloat(document.getElementById('totalPrice' + i).value,10)
          }
          return totalWithoutVAT;
        }

        function insertServices()
        {
          var indexItIsFoundAt = -1;
          for(let i = 1; i < numberOfLastRow; i++)
          {
            if(document.getElementById("productName" + i).value == productArray[5][0])
            {
              indexItIsFoundAt = i;
              break;
            }
          }
          if(indexItIsFoundAt == -1)
          {
            addRow();
            var inputField = document.getElementById("productName" + (numberOfLastRow-1));
            inputField.value = productArray[5][0];

            inputField = document.getElementById("measurementUnit"+ (numberOfLastRow-1));
            inputField.value = ' ';

            inputField = document.getElementById("quantity"+ (numberOfLastRow-1));
            inputField.value = 1;

            inputField = document.getElementById("unitPrice"+ (numberOfLastRow-1));
          }else {
            inputField = document.getElementById("unitPrice"+ indexItIsFoundAt);
          }

          var totalWithoutVAT = document.getElementById("totalWithoutVAT").value;
          inputField.value = totalWithoutVAT - parseFloat(computeTotalWithoutVAT(),10);

          getTotalPrice(numberOfRows);
          getVAT(numberOfRows);

          document.getElementById("btn2").style.visibility = "hidden";
        }
    </script>

  </head>
  <body onload="addRow()">
    <div class="page"> <!-- Contains the whole page (white background) -->
      <form clas="invoiceForm" action="generateInvoice.php" method="post">
      <div class="header"> <!-- Contains the upper part of the page with information about provider and customer-->
        <div class="provider" > <!-- Holds information about the provider -->
          <table class="providerTable" border="0">
            <tr> <td colspan="2">  <input readonly name="pName" type="text" class="info firmName" value="NYUSZIKA DEPOZIT MATERIALE CONSTRUCTII SRL"><br> </td> </tr>
            <tr> <td class="infoHolderTd"> <div class="data"><label for="pNrOrdRc"  class="label">Nr.ord.RC/an: </label></td>  <td class="dataHolderTd"> <input readonly name="pNrOrdRc" type="text" class="info" value="J12/2171/1995"><br></div> </td> </tr>
            <Tr> <td class="infoHolderTd"> <div class="data"><label for="pCIF" class="label">C.I.F.: </label> </td>        <td class="dataHolderTd"> <input readonly name="pCIF" type="text" class="info" value="RO7978513"><br></div> </td> </tr>
            <tr> <td class="infoHolderTd"> <div class="data"><label for="pOffice" class="label">Sediu: </label> </td>        <td class="dataHolderTd"> <input readonly name="pOffice" type="text" class="info" value="Gilău, Zona Industrială Braniște, F.N."><br></div> </td> </tr>
            <tr> <td class="infoHolderTd"> <div class="data"><label for="pCounty" class="label">Județul: </label> </td>      <td class="dataHolderTd"> <input readonly name="pCounty" type="text" class="info" value="Cluj"><br></div> </td> </tr>
            <tr> <td class="infoHolderTd"> <div class="data"><label for="pBankAccount1" class="label">Cont: </label> </td>        <td class="dataHolderTd">  <input readonly name="pBankAccount1" type="text" class="info" value="RO06 BTRL 0130 1202 D363 94XX"><br></div> </td> </tr>
            <tr> <td class="infoHolderTd"> <div class="data"><label for="pBank1" class="label">Banca: </label> </td>        <td class="dataHolderTd"> <input readonly name="pBank1" type="text" class="info" value="Transilvania"><br></div> </td> </tr>
            <tr> <td class="infoHolderTd"> <div class="data"><label for="pBankAccount2" class="label">Cont: </label> </td>         <td class="dataHolderTd">  <input readonly name="pBankAccount2" type="text" class="info" value="RO88 RNCB 0106 0266 1480 0001"><br></div> </td> </tr>
            <tr> <td class="infoHolderTd"> <div class="data"><label for="pBank2" class="label">Banca: </label> </td>         <td class="dataHolderTd"> <input readonly name="pBank2" type="text" class="info" value="Comercială Română"><br></div> </td> </tr>
          </table>
        </div>
        <div class="title"> <!-- Title part of the invoice -->
          <table border="0" class="titleTable">
              <tr>
                <td colspan="2" class="titleTxt">  FACTURĂ </td>
              </tr>
              <tr>
                <td class="label">Data: </td>
                <td class="info"> <input name="invoiceDate" class="editable info" type="date"> </td>
              </tr>
              <tr>
                <td class="label"> Serie/Număr: </td>
                <td > <input name="serialNumber" type="text" class="editable info" value="NYU " > </td>
              </tr>
          </table>
        </div>
        <div class="customer"> <!-- Holds information about the customer -->
          <table class="customerTable" border="0">
            <tr>
              <td class="infoHolderTd"> <div class="data"><label for="companySelect" class="label">Cumpărător:</label></td>
              <td style="position:relative;">
                <input id="nfName" name="newFirmName" type="text" style="position: absolute;top: 0;visibility: <?php echo $visibilityInputNewFirm?>;" class="inputNewFirm">
                  <input name="existingFirmName" type="text" style="position: absolute;top: 0;visibility: <?php echo $visibilityInputExistingFirm?>;" <?php echo $valueInputExistingFirm?> class="formElement companySelect" readonly>
                  <select  style="visibility: <?php echo $visibilityListExistingFirm?>;" class="companySelect editable" id="firmName" class="formElement" name="companySelect" onChange="loadData()" method="get">
                    <option value="-">Selectează firma</option>
                    <option value="newFirm">Firmă nouă</option>
                    <?php
                      while($qResult = mysqli_fetch_array($query,MYSQLI_ASSOC))
                      {
                    ?>
                    <option value="<?php echo $qResult['NAME'] ?>"> <?php echo $qResult['NAME'] ?> </option>
                  <?php } ?>

                </select> <br>
              </div> </td>
            </tr>
              <tr> <td class="infoHolderTd"> <div class="data">
                <label for="cNrOrdRc" class="label">Nr.ord.RC/an: </label> </td> <td class="dataHolderTd">
                <input id="nfNrOrd" name="newFirmNrOrd" type="text" class="inputNewFirm" style="position: absolute; top: 0; visibility: <?php echo $visibilityInputNewFirm ?>;">
                <input name="existingFirmNrOrd" readonly style="visibility: <?php echo $visibilityInputExistingFirm?>;width: 200px;" name="cNrOrdRc" type="text" class="info" value="<?php echo $nrOrd ?>" > <br></div>
               </td> </tr>
              <tr> <td class="infoHolderTd"> <div class="data">
                <label for="cCIF" class="label">C.I.F.: </label>   </td> <td class="dataHolderTd">
                <input id="nfCIF" name="newFirmCIF" type="text" class="inputNewFirm" style="position: absolute; top: 0; visibility: <?php echo $visibilityInputNewFirm ?>;">
                <input name="existingFirmCIF" readonly style="visibility: <?php echo $visibilityInputExistingFirm?>; width: 200px;" name="cCIF" type="text" class="info" value="<?php echo $CIF ?>"><br></div>
              </td> </tr>
              <tr> <td class="infoHolderTd"> <div class="data">
                <label for="cOffice" class="label">Sediu: </label>    </td> <td class="dataHolderTd">
                <input id="nfOffice" name="newFirmOffice" type="text" class="inputNewFirm" style="position: absolute; top: 0; visibility: <?php echo $visibilityInputNewFirm ?>;">
                <input  name="existingFirmOffice" readonly style="visibility: <?php echo $visibilityInputExistingFirm?>; width: 200px;" name="cOffice" type="text" class="info" value="<?php echo $office ?>"><br></div>
              </td> </tr>
              <tr> <td class="infoHolderTd"> <div class="data">
                <label for="cCounty" class="label">Județul: </label>    </td> <td class="dataHolderTd">
                <input id="nfCounty" name="newFirmCounty" type="text" class="inputNewFirm" style="position: absolute; top: 0; visibility: <?php echo $visibilityInputNewFirm ?>;">
                <input  name="existingFirmCounty" readonly style="visibility: <?php echo $visibilityInputExistingFirm?>; width: 200px;" name="cCounty" type="text" class="info" value="<?php echo $county ?>" ><br></div>
              </td> </tr>
              <tr> <td class="infoHolderTd"> <div class="data">
                <label for="cBankAccount1" class="label">Cont: </label>   </td> <td class="dataHolderTd">
                <input id="nfBankAccount" name="newFirmBankAccount" type="text" class="inputNewFirm" style="position: absolute; top: 0; visibility: <?php echo $visibilityInputNewFirm ?>;">
                <input name="existingFirmBankAccount"  readonly style="visibility: <?php echo $visibilityInputExistingFirm?>; width: 200px;" name="cBankAccount1" type="text" class="info" value="<?php echo $bankAccount ?>"><br></div>
              </td> </tr>
              <tr> <td class="infoHolderTd"> <div class="data">
                <label for="cBank1" class="label">Banca: </label>    </td> <td class="dataHolderTd">
                <input id="nfBank" name="newFirmBank" type="text" class="inputNewFirm" style="position: absolute; top: 0; visibility: <?php echo $visibilityInputNewFirm ?>;">
                <input  name="existingFirmBank" readonly style="visibility: <?php echo $visibilityInputExistingFirm?>; width: 200px;" name="cBank1" type="text" class="info" value="<?php echo $bank ?>"><br></div>
              </td> </tr>
            </table>
        </div>
      </div>
      <div class="content">
        <div class="commandButtonContainer">
          <div class="commandButton cbleft" id="btn1" onclick="removeRow();clickedEffect(1)">Ștergere rând</div>
          <div class="commandButton cbright" id="btn2" onclick="addRow();clickedEffect(2)">Adăugare rând</div>
        </div>

        <table border="1" class="contentTable" id="contentTable" >
          <tr class="contentHeaderRow">
            <td> Nr. crt. </td>
            <td> Denumirea produselor sau a serviciilor </td>
            <td> U.M. </td>
            <td> Cantitatea </td>
            <td> Preț unitar (fără T.V.A.) <br> -ron- </td>
            <td> Valoare <br> -ron- </td>
            <td> Valoare T.V.A. <br> -ron- </td>
          </tr>
        </table>
      </div>
      <div class="footer" class="footer">
        <table border="1" class="footerTable">
          <tr >
            <td rowspan="3"> <div id="signatureAndStamp"> Semnătura și ștampila furnizorului: </div> </td>
            <td rowspan="3">
              <b>Date privind expediția:</b><br>
              <label for="delegatNume">Numele delegatului:</label> <input type="text" name="delegateName">  <br>
              <label for="delegatSeria">B.I./C.I. seria:</label> <input type="text" name="delegateIdSeries" style="width: 30px;">
              <label for="delegatNr">nr.</label> <input type="text" name="delegateIdNumber" style="width: 60px;">
              <label for="delegatEliberat">eliberat(ă)</label> <input type="text" name="delegateIdIssued" style="width: 140px;">  <br>
              <label for="delegatMijTrans">Mijloc de transport:</label> <input type="text"  name="delegateVehicle" style="width: 100px;">
              <label for="delegatMijTransNr">nr.</label> <input type="text" name="delegateVehicleLicensePlateNr" style="width:80px;">  <br>
              <label for="delegatData">Expedierea s-a făcut în prezența noastră la data de:</label> <input type="date" name="delegateDate">
              <label for="delegatOra">ora:</label> <input type="time" name="delegateHour">  <br>
              Semnăturile:
            </td>
            <td rowspan="3"> <div style="margin: 10px;"> <B> Total </B> </div>
            </td>
            <td class="centerTXT"> Valoare RON</td>
            <td class="centerTXT"> Valoare TVA </td>
          </tr>
          <tr>
            <td>
              <input type="number" id="totalWithoutVAT" name="totalWithoutVAT" style="border: 1px solid black;width: 80px;margin-left: 10px;margin-right:10px;" class="info centerTXT" onchange="insertServices()">
            </td>
            <td>
              <input type="number" id="totalVAT" readonly name="totalVAT" class="info centerTXT">
            </td>
          </tr>
          <tr>
            <td colspan="4">
              <input type="number" id="total" readonly name="total" class="info centerTXT">
            </td>
          </tr>
        </table>
          <input class="generateBTN" type="submit" value="Generare factură">
      </div>
    </div>
      <input type="number" style="visibility:hidden;" name="numberOfProducts" id='numberOfProducts'>
      </form>
    </div>
    <form style="visibility: hidden;" action="requestData.php" id="auxiliarForm" method="post">
        <input type="text" name="firmName" id="firmNameH">
    </form>
  </body>
</html>
