<?php
	session_start();
	$nota	= $_GET[nota];
	require("fpdf_protection.php");
	include "conn_db.php";

	$sql	= "select a.kd_unit,e.nm_unit,a.kd_lang,b.nm_supplier,a.op_do,a.tgljttempo,a.buk_ref,a.tanggal,a.tgl_sjnota,a.sj_nota,a.kd_brg,c.nm_barang,a.kd_sat,d.nm_sat,a.jm_kwt,a.jm_kwt_bns,a.h_beli,a.jm_hrg from t_penerimaan a,m_supplier b ,m_barang c,m_satuan d,m_unit e where a.kd_lang = b.kd_supplier and bukti like 'BI%' and bukti='$nota' and a.kd_brg = c.kd_barang and a.kd_sat = d.kd_sat and a.kd_unit = e.kd_unit";
	$qsql		= mysql_query($sql);
	$Rqsql		= mysql_fetch_assoc($qsql);
	$rr			= $Rqsql["buk_ref"];
	$kdsup		= $Rqsql["kd_lang"];
	$nmsup		= $Rqsql["nm_supplier"];
	$opdo		= $Rqsql["op_do"];
	$tgljtmp	= $Rqsql["tgljttempo"];
	$tglcet		= $Rqsql["tanggal"];
	$tglsjnota	= $Rqsql["tgl_sjnota"];
	$sjnota		= $Rqsql["sj_nota"];
	$tglsjexp	= explode("-",$tglsjnota);
	$tglsjcon	= $tglsjexp[2]."-".$tglsjexp[1]."-".$tglsjexp[0];

	class PDF extends FPDF
	{
		//===============MEMBUAT HEADER=======================
		function Header()
		{
			global $kdsup;
			global $nmsup;
			global $rr;
			global $opdo;
			global $tgljtmp;
			global $tglcet;
			global $sjnota;
			global $tglsjcon;

			$tglcetexp	= explode("-",$tglcet);
			$tglcetcon	= $tglcetexp[2]."-".$tglcetexp[1]."-".$tglcetexp[0];
			$tgljtmpexp	= explode("-",$tgljtmp);
			$tgljtmpcon	= $tgljtmpexp[2]."-".$tgljtmpexp[1]."-".$tgljtmpexp[0];
		
			$this->SetMargins(0.1,0.1,0.1,0.1);
			$this->Ln();
			$this->Ln();
			$this->Ln();
			$this->SetFont('times','',9);  	
			$this->Cell(0.2,0.4,'','',0,'L');	
			$this->Cell(5.5,0.4,'KOPERASI WARGA SEMEN GRESIK','',0,'L');	
			$this->Cell(8.8,0.4,'','',0,'');
			$this->Cell(3.0,0.4,'Nomor','',0,'L');
			$this->Cell(0.5,0.4,':','',0,'');
			$this->Cell(2.6,0.4,$rr,0,'TR');
			$this->Cell(0.2,0.4,'','',0,'L');	
			$this->Ln();
			$this->Cell(0.2,0.4,'','',0,'L');
			$this->Cell(5.5,0.4,'UNIT LOGISTIK NON SEMEN','',0,'L');
			$this->Cell(8.8,0.4,'','',0,'');
			$this->Cell(3.0,0.4,'Tanggal Terima','',0,'L');
			$this->Cell(0.5,0.4,':','',0,'');
			$this->Cell(2.6,0.4,$tglcetcon,0,'L');
			$this->Cell(0.2,0.4,'','',0,'L');	
			$this->Ln();
			$this->SetFont('times','BU',9);  
			$this->Cell(0.2,0.4,'','',0,'L');
			$this->Cell(18.3,0.4,'BUKTI PENERIMAAN BARANG','',0,'C');
			$this->Cell(2.1,4,'','',0,'L');
			$this->Cell(0.2,0.4,'','',0,'L');	
			$this->Ln();
			$this->SetFont('times','',9);  
			$this->Cell(0.2,0.4,'','',0,'L');
			$this->Cell(2.0,0.4,'Kode Vendor','',0,'L');
			$this->Cell(0.5,0.4,':','',0,'C');
			$this->Cell(6.0,0.4,$kdsup,'',0,'L');
			$this->Cell(5.8,0.4,'','',0,'L');
			$this->Cell(3.0,0.4,'Nomor OP/DO','',0,'L');
			$this->Cell(0.5,0.4,':','',0,'L');
			$this->Cell(2.6,0.4,$opdo,'',0,'L');
			$this->Cell(0.2,0.4,'','',0,'L');	
			$this->Ln();
			$this->Cell(0.2,0.4,'','',0,'L');
			$this->Cell(2.0,0.4,'Nama Vendor','',0,'L');
			$this->Cell(0.5,0.4,':','',0,'C');
			$this->Cell(6.0,0.4,$nmsup,'',0,'L');
			$this->Cell(5.8,0.4,'','',0,'L');
			$this->Cell(3.0,0.4,'No.SPJ','',0,'L');
			$this->Cell(0.5,0.4,':','',0,'L');
			$this->Cell(2.6,0.4,$sjnota,'',0,'L');
			$this->Cell(0.2,0.4,'','',0,'L');	
			$this->Ln();
			$this->Cell(0.2,0.4,'','',0,'L');
			$this->Cell(2.0,0.4,'Tgl.Jt.Tempo','',0,'L');
			$this->Cell(0.5,0.4,':','',0,'C');
			$this->Cell(6.0,0.4,$tgljtmpcon,'',0,'L');
			$this->Cell(5.8,0.4,'','',0,'L');
			$this->Cell(3.0,0.4,'Tgl.SPJ','',0,'L');
			$this->Cell(0.5,0.4,':','',0,'L');
			$this->Cell(2.6,0.4,$tglsjcon,'',0,'L');
			$this->Cell(0.2,0.4,'','',0,'L');	
			$this->Ln();
			$this->Cell(0.0,0.4,'','',0,0);
			$this->Ln();
			$this->Cell(0.2,0.4,'','',0,'L');
			$this->Cell(3.4,0.4,'Unit','TRBL',0,'C');
			$this->Cell(1.7,0.4,'Kode','TRL',0,'C');
			$this->Cell(7.3,0.4,'Nama Barang','TRL',0,'C');
			$this->Cell(1.5,0.4,'Satuan','TRL',0,'C');
			$this->Cell(1.0,0.4,'Kwt','TRL',0,'C');
			$this->Cell(0.8,0.4,'Bns','TRL',0,'C');
			$this->Cell(4.7,0.4,'Harga Beli','TRBL',0,'C');
			$this->Cell(0.2,0.4,'','',0,'L');
			$this->Ln();
			$this->Cell(0.2,0.4,'','',0,'L');
			$this->Cell(0.8,0.4,'Kode','TRBL',0,'C');
			$this->Cell(2.6,0.4,'Nama','TRBL',0,'C');
			$this->Cell(1.7,0.4,'Barang','RBL',0,'C');
			$this->Cell(7.3,0.4,'','RBL',0,'C');
			$this->Cell(1.5,0.4,'','RBL',0,'C');
			$this->Cell(1.0,0.4,'','RBL',0,'C');
			$this->Cell(0.8,0.4,'','RBL',0,'C');
			$this->Cell(2.3,0.4,'Satuan','TRBL',0,'C');
			$this->Cell(2.4,0.4,'Total','TRBL',0,'C');
			$this->Cell(0.2,0.4,'','',0,'L');
			$this->Ln();
		}
	}

	$pdf = new PDF('P','cm','A4');

	$pdf->Open();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$rsql	= mysql_query($sql);
	while($recDat = mysql_fetch_array($rsql)){
		$kdbrg		= $recDat["kd_brg"];
		$nmbrg		= $recDat["nm_barang"];
		$sat		= $recDat["nm_sat"];
		$kwt		= $recDat["jm_kwt"];
		$hsat		= $recDat["h_beli"];
		$jmhrg		= $recDat["jm_hrg"];
		$kdunit		= $recDat["kd_unit"];
		$nmunit		= $recDat["nm_unit"];
		$kwtbns		= $recDat["jm_kwt_bns"];
		
		$pdf->Cell(0.2,0.6,'','',0,'L');
		$pdf->Cell(0.8,0.6,$kdunit,'TRBL',0,'C');
		$pdf->Cell(2.6,0.6,$nmunit,'TRBL',0,'C');
		$pdf->Cell(1.7,0.6,$kdbrg,'RBL',0,'C');
		$pdf->Cell(7.3,0.6,substr($nmbrg,0,42),'RBL',0,'L');
		$pdf->Cell(1.5,0.6,$sat,'RBL',0,'C');
		$pdf->Cell(1.0,0.6,number_format($kwt,0,'.',','),'RBL',0,'R');
		$pdf->Cell(0.8,0.6,number_format($kwtbns,0,'.',','),'RBL',0,'R');
		$pdf->Cell(2.3,0.6,number_format($hsat,2,'.',','),'TRBL',0,'R');
		$pdf->Cell(2.4,0.6,number_format($jmhrg,2,'.',','),'TRBL',0,'R');
		$pdf->Cell(0.2,0.6,'','',0,'L');
		$pdf->Ln();
		$totjmhrg	+= $jmhrg;
	}
	
	$pdf->Cell(0.2,0.4,'','',0,'L');
	$pdf->Cell(13.9,0.4,'Keterangan : ','L',0,'L');
	$pdf->Cell(4.1,0.4,'Total','BLR',0,'C');
	$pdf->Cell(2.4,0.4,number_format($totjmhrg,2,'.',','),'BR',0,'R');
	$pdf->Cell(0.2,0.4,'','',0,'R');
	$pdf->Ln();
	$pdf->Cell(0.2,0.4,'','',0,'L');
	$cektk	= "select bukti from t_penerimaan where buk_ref='$nota' group by bukti";
	$qcektk	= mysql_query($cektk);
	$qcekcount = mysql_query($cektk);
	$a = 0;
	while($datcount = mysql_fetch_array($qcekcount)){
		$bukti[$a] = $datcount["bukti"];	
		$a++;
	}
	$rcount = count($bukti);
	$pdf->Cell(13.9,0.4,'','L',0,'L');
	$pdf->Cell(4.1,0.4,'Manager Logistik','RL',0,'L');
	$pdf->Cell(2.4,0.4,'Ass.Manager Log','RL',0,'L');
	$pdf->Cell(0.2,0.4,'','',0,'R');
	$pdf->Ln();
	
	while($datcektk = mysql_fetch_array($qcektk))
	{
		$buktitkx	= $datcektk["bukti"];
		$pdf->Cell(0.2,0.4,'','',0,'L');
		$pdf->Cell(13.9,0.4,$buktitkx,'L',0,'L');
		$pdf->Cell(4.1,0.4,'','RL',0,'C');
		$pdf->Cell(2.4,0.4,'','RL',0,'R');
		$pdf->Cell(0.2,0.4,'','',0,'R');
		$pdf->Ln();

	}	
	if($rcount == 2)
	{
		$xcount = $rcount;
	}
	else if($rcount == 1)
	{
		$xcount = $rcount + 1;
	}
	else{
		$xcount = 0;
	}
	for($x=0;$x<$xcount;$x++)
	{
		$pdf->Cell(0.2,0.4,'','',0,'L');
		$pdf->Cell(13.9,0.4,'','L',0,'L');
		$pdf->Cell(4.1,0.4,'','RL',0,'C');
		$pdf->Cell(2.4,0.4,'','RL',0,'R');
		$pdf->Cell(0.2,0.4,'','',0,'R');
		$pdf->Ln();
	}
	$pdf->Cell(0.2,0.4,'','',0,'L');
	$pdf->Cell(13.9,0.4,'','L',0,'L');
	$pdf->Cell(4.1,0.4,'Ali Rif`an','BLR',0,'L');
	$pdf->Cell(2.4,0.4,'Jony Ali S','BLR',0,'L');
	$pdf->Cell(0.2,0.4,'','',0,'R');
	$pdf->Ln();
	$pdf->Cell(0.2,0.4,'','',0,'L');
	$pdf->Cell(13.9,0.4,'','BRL',0,'L');
	$pdf->Cell(6.5,0.4,'Logistik Non Semen','BR',0,'C');
	$pdf->Cell(0.2,0.4,'','',0,'R');
	$pdf->Ln();
	$pdf->Output();
?>
