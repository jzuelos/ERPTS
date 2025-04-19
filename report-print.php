<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Assessment Roll</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 30px;
    }

    h1 {
      text-align: center;
      margin-bottom: 10px;
      font-size: 24px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12px;
    }

    th, td {
      border: 1px solid #000;
      padding: 6px;
      text-align: left;
      vertical-align: top;
    }

    th {
      background-color: #f0f0f0;
    }

    .sub-header th {
      text-align: center;
    }

    .nowrap {
      white-space: nowrap;
    }

    .location-header td {
      border: none;
      padding: 4px 6px;
      font-weight: bold;
    }

    .location-header {
      margin-bottom: 5px;
    }
  </style>
</head>
<body>

  <h1>ASSESSMENT ROLL</h1>

  <table class="location-header">
    <tr>
      <td><strong>PROVINCE/CITY:</strong> CAMARINES NORTE</td>
      <td><strong>MUNICIPALITY:</strong> DAET</td>
      <td><strong>DISTRICT:</strong> (INSERT)</td>
      <td style="text-align: right;"><strong>BARANGAY:</strong> (INSERT)</td>
    </tr>
  </table>

  <table>
    <thead>
      <tr class="sub-header">
        <th rowspan="2">PROPERTY OWNER</th>
        <th rowspan="2">PROPERTY INDEX NO.</th>
        <th rowspan="2">ARP NO.</th>
        <th rowspan="2">OWNER'S ADDRESS & TEL. NOS.</th>
        <th rowspan="2">KIND</th>
        <th rowspan="2">CLASS</th>
        <th rowspan="2">LOCATION OF PROPERTY</th>
        <th rowspan="2">ASSESSED VALUE</th>
        <th rowspan="2">TAXABILITY</th>
        <th rowspan="2">EFFECTIVITY</th>
        <th colspan="3">CANCELS</th>
        <th colspan="4">CANCELLED BY</th>
      </tr>
      <tr class="sub-header">
        <th class="nowrap">UPDATE CODE</th>
        <th class="nowrap">ARP NO.</th>
        <th class="nowrap">ASSESSED VALUE</th>
        <th class="nowrap">UPDATE CODE</th>
        <th class="nowrap">ARP NO.</th>
        <th class="nowrap">ASSESSED VALUE</th>
        <th class="nowrap">REF NO.</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Example Owner</td>
        <td>025-03-009-16-117</td>
        <td>GR-2005-03-009-03543</td>
        <td>Sample Address, Alawihao, Daet</td>
        <td>Land</td>
        <td>Residential</td>
        <td>Alawihao, Daet</td>
        <td>10,110.00</td>
        <td>Taxable</td>
        <td>2007</td>
        <td>GR</td>
        <td>ARP-0001</td>
        <td>10,110.00</td>
        <td>GR</td>
        <td>ARP-0002</td>
        <td>10,500.00</td>
        <td>11223344</td>
      </tr>
      <!-- Add more rows as needed -->
    </tbody>
  </table>

</body>
</html>
