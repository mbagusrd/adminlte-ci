<?php
	session_start();
	$nota	= $_GET[nota];
	require("fpdf_protection.php");
	include "conn_db.php";

	$sql	= "select a.kd_lang,a.op_do,a.tgljttempo,a.bukti,a.tanggal,a.tgl_sjnota,a.sj_nota,a.kd_brg,c.nm_barang,a.kd_sat,d.nm_sat, a.jm_kwt,a.jm_kwt_bns,a.h_beli,a.jm_hrg,a.no_kon,a.kd_ke,b.nm_unit from t_penerimaan a,m_barang c,m_satuan d,m_unit b where a.bukti like 'TK%' and a.bukti='$nota' and a.kd_brg = c.kd_barang and a.kd_sat = d.kd_sat and a.kd_ke = b.kd_unit";
	$qsql		= mysql_query($sql);
	$Rqsql		= mysql_fetch_assoc($qsql);
	$buktitk	= $Rqsql["bukti"];
	$kddr		= $Rqsql["kd_lang"];
	$kdke		= $Rqsql["kd_ke"];
	$nmunit		= $Rqsql["nm_unit"];
	$tglcet		= $Rqsql["tanggal"];
	$tglcetexp	= explode("-",$tglcet);
	$tglcetcon	= $tglcetexp[2]."-".$tglcetexp[1]."-".$tglcetexp[0];
	
	class PDF extends FPDF
	{
		//===============MEMBUAT HEADER=======================
		function Header()
		{
			global $kddr;
			global $kdke;
			global $buktitk;
			global $tgljtmp;
			global $tglcetcon;
			global $nmunit;
		
			$this->SetMargins(0.1,0.1,0.1,0.1);
			$this->Ln();
			$this->Ln();
			$this->Ln();
			$this->SetFont('courier','',10);  	
			$this->Cell(0.2,0.4,'','',0,'L');	
			$this->Cell(5.5,0.4,'KOPERASI WARGA SEMEN GRESIK','',0,'L');	
			$this->Cell(8.8,0.4,'','',0,'');
			$this->Cell(2.6,0.4,'Nomor','',0,'L');
			$this->Cell(0.1,0.4,':','',0,'');
			$this->Cell(3.4,0.4,$buktitk,0,'TR');
			$this->Cell(0.2,0.4,'','',0,'L');	
			$this->Ln();
			$this->Cell(0.2,0.4,'','',0,'L');
			$this->Cell(5.5,0.4,'UNIT LOGISTIK NON SEMEN','',0,'L');
			$this->Cell(8.8,0.4,'','',0,'');
			$this->Cell(2.6,0.4,'Tanggal Terima','',0,'L');
			$this->Cell(0.1,0.4,':','',0,'');
			$this->Cell(3.4,0.4,$tglcetcon,0,'L');
			$this->Cell(0.2,0.4,'','',0,'L');	
			$this->Ln();
			$this->SetFont('courier','BU',10);  
			$this->Cell(0.2,0.4,'','',0,'L');
			$this->Cell(18.3,0.4,'BUKTI TRANSFER ANTAR UNIT','',0,'C');
			$this->Cell(2.1,4,'','',0,'L');
			$this->Cell(0.2,0.4,'','',0,'L');	
			$this->Ln();
			$this->SetFont('courier','',10);  
			$this->Cell(0.2,0.4,'','',0,'L');
			$this->Cell(2.0,0.4,'Dikirim Dari','',0,'L');
			$this->Cell(0.5,0.4,':','',0,'C');
			$this->Cell(6.0,0.4,$kddr.' / LOGISTIK NON SEMEN','',0,'L');
			$this->Cell(5.8,0.4,'','',0,'L');
			$this->Cell(2.6,0.4,'Diterima Oleh','',0,'L');
			$this->Cell(0.1,0.4,':','',0,'L');
			$this->Cell(3.4,0.4,$kdke." / ".$nmunit,'',0,'L');
			$this->Cell(0.2,0.4,'','',0,'L');	
			$this->Ln();
			$this->Cell(0.0,0.4,'','',0,0);
			$this->Ln();
			$this->Cell(0.2,0.4,'','',0,'L');
			$this->Cell(0.7,0.4,'No','TRL',0,'C');
			$this->Cell(1.7,0.4,'Kode','TRL',0,'C');
			$this->Cell(1.8,0.4,'No.Kontr','TRL',0,'C');
			$this->Cell(6.9,0.4,'Nama Barang','TRL',0,'C');
			$this->Cell(1.5,0.4,'Satuan','TRL',0,'C');
			$this->Cell(1.0,0.4,'Kwt','TRL',0,'C');
			$this->Cell(0.8,0.4,'Bns','TRL',0,'C');
			$this->Cell(4.2,0.4,'Harga Rata-rata','TRBL',0,'C');
			$this->Cell(1.8,0.4,'Harga','TRBL',0,'C');
			$this->Cell(0.2,0.4,'','',0,'L');
			$this->Ln();
			$this->Cell(0.2,0.4,'','',0,'L');
			$this->Cell(0.7,0.4,'','RBL',0,'C');
			$this->Cell(1.7,0.4,'Barang','RBL',0,'C');
			$this->Cell(1.8,0.4,'','RBL',0,'C');
			$this->Cell(6.9,0.4,'','RBL',0,'C');
			$this->Cell(1.5,0.4,'','RBL',0,'C');
			$this->Cell(1.0,0.4,'','RBL',0,'C');
			$this->Cell(0.8,0.4,'','RBL',0,'C');
			$this->Cell(1.8,0.4,'Satuan','TRBL',0,'C');
			$this->Cell(2.4,0.4,'Total','TRBL',0,'C');
			$this->Cell(1.8,0.4,'Beli','TRBL',0,'C');
			$this->Cell(0.2,0.4,'','',0,'L');
			$this->Ln();
		}
	}

	$pdf = new PDF('P','cm','A3');

	$pdf->Open();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$rsql	= mysql_query($sql);
	$no	= 1;
	while($recDat = mysql_fetch_array($rsql)){
		$nokon		= $recDat["no_kon"];
		$kdbrg		= $recDat["kd_brg"];
		$nmbrg		= $recDat["nm_barang"];
		$sat		= $recDat["nm_sat"];
		$kwt		= $recDat["jm_kwt"];
		$hsat		= $recDat["h_beli"];
		$jmhrg		= $recDat["jm_hrg"];
		$kwtbns		= $recDat["jm_kwt_bns"];
		$tglsjexp	= explode("-",$tglsjnota);
		$tglsjcon	= $tglsjexp[2]."-".$tglsjexp[1]."-".substr($tglsjexp[0],2,2);
		$pdf->Cell(0.2,0.6,'','',0,'L');
		$pdf->Cell(0.7,0.6,$no,'RLB',0,'C');
		$pdf->Cell(1.7,0.6,$kdbrg,'RBL',0,'C');
		$pdf->Cell(1.8,0.6,$nokon,'RBL',0,'C');
		$pdf->Cell(6.9,0.6,substr($nmbrg,0,42),'RBL',0,'L');
		$pdf->Cell(1.5,0.6,$sat,'RBL',0,'C');
		$pdf->Cell(1.0,0.6,number_format($kwt,0,'.',','),'RBL',0,'R');
		$pdf->Cell(0.8,0.6,number_format($kwtbns,0,'.',','),'RBL',0,'R');
		$pdf->Cell(1.8,0.6,number_format($hsat,2,'.',','),'TRBL',0,'R');
		$pdf->Cell(2.4,0.6,number_format($jmhrg,2,'.',','),'TRBL',0,'R');
		$pdf->Cell(1.8,0.6,number_format($hsat,2,'.',','),'TRBL',0,'R');
		$pdf->Cell(0.2,0.6,'','',0,'L');
		$pdf->Ln();
		$totjmhrg	+= $jmhrg;
		$no++;
	}
	
	$pdf->Cell(0.2,0.4,'','',0,'L');
	$pdf->Cell(11.1,0.4,'Keterangan : ','L',0,'L');
	$pdf->Cell(5.1,0.4,'Total','BLR',0,'L');
	$pdf->Cell(2.4,0.4,number_format($totjmhrg,2,'.',','),'BR',0,'R');
	$pdf->Cell(1.8,0.4,'','RB',0,'R');
	$pdf->Cell(0.2,0.4,'','',0,'R');
	$pdf->Ln();
	$pdf->Cell(0.2,0.4,'','',0,'L');
	$cektk	= "select a.buk_ref,b.alamat_kirim from t_penerimaan a,t_op b where a.bukti='$nota' and b.bukti_op = a.op_do group by a.bukti";
	$qcektk	= mysql_query($cektk);
	while($datTk = mysql_fetch_array($qcektk)){
		$buktiref	= $datTk["buk_ref"];
		$almtkrm	= $datTk["alamat_kirim"];
		$pdf->Cell(11.1,0.4,$buktiref,'L',0,'L');
	}
	$pdf->Cell(2.5,0.4,'Diterima oleh:','RL',0,'L');
	$pdf->Cell(2.6,0.4,'Mengetahui,','R',0,'L');
	$pdf->Cell(2.4,0.4,'','R',0,'L');
	$pdf->Cell(1.8,0.4,'Dikirim oleh:','R',0,'R');
	$pdf->Cell(0.2,0.4,'','',0,'R');
	$pdf->Ln();
	$pdf->Cell(0.2,0.4,'','',0,'L');
	$pdf->Cell(11.1,0.4,"Alamat : ".substr($almtkrm,0,45),'L',0,'L');
	$pdf->Cell(2.5,0.4,'','RL',0,'C');
	$pdf->Cell(2.6,0.4,'Manager Logistik','R',0,'L');
	$pdf->Cell(2.4,0.4,'Ass.Manager','R',0,'L');
	$pdf->Cell(1.8,0.4,'','R',0,'R');
	$pdf->Cell(0.2,0.4,'','',0,'R');
	$pdf->Ln();
	$pdf->Cell(0.2,0.4,'','',0,'L');
	$pdf->Cell(11.1,0.4,substr($almtkrm,46,200),'L',0,'L');
	$pdf->Cell(2.5,0.4,'','LR',0,'C');
	$pdf->Cell(2.6,0.4,'','R',0,'L');
	$pdf->Cell(2.4,0.4,'','R',0,'L');
	$pdf->Cell(1.8,0.4,'','R',0,'R');
	$pdf->Cell(0.2,0.4,'','',0,'R');
	$pdf->Ln();
	$pdf->Cell(0.2,0.4,'','',0,'L');
	$pdf->Cell(11.1,0.4,'','L',0,'L');
	$pdf->Cell(2.5,0.4,'','LR',0,'C');
	$pdf->Cell(2.6,0.4,'','R',0,'L');
	$pdf->Cell(2.4,0.4,'','R',0,'L');
	$pdf->Cell(1.8,0.4,'','R',0,'R');
	$pdf->Cell(0.2,0.4,'','',0,'R');
	$pdf->Ln();
	$pdf->Cell(0.2,0.4,'','',0,'L');
	$pdf->Cell(11.1,0.4,'','LR',0,'L');
	$pdf->Cell(2.5,0.4,'','R',0,'L');
	$pdf->Cell(2.6,0.4,'Ali Rif`an','BR',0,'L');
	$pdf->Cell(2.4,0.4,'Jony Ali S.','BR',0,'L');
	$pdf->Cell(1.8,0.4,'','R',0,'R');
	$pdf->Cell(0.2,0.4,'','',0,'R');
	$pdf->Ln();
	$pdf->Cell(0.2,0.4,'','',0,'L');
	$pdf->Cell(11.1,0.4,'','BL',0,'L');
	$pdf->Cell(2.5,0.4,'','BLR',0,'L');
	$pdf->Cell(5.0,0.4,'Logistik Non Semen','BR',0,'C');
	$pdf->Cell(1.8,0.4,'','BR',0,'R');
	$pdf->Cell(0.2,0.4,'','',0,'R');
	$pdf->Ln();
	$pdf->Output();
?>
