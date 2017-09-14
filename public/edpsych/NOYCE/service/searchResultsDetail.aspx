<%@ Register TagPrefix="uc1" TagName="footer" Src="footer.ascx" %>
<%@ Page Language="vb" AutoEventWireup="false" Codebehind="searchResultsDetail.aspx.vb" Inherits="NOYCE.searchResultsDetail"%>
<%@ Register TagPrefix="uc1" TagName="header" Src="header.ascx" %>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<title>searchResultsDetail</title>
		<meta name="GENERATOR" content="Microsoft Visual Studio .NET 7.1">
		<meta name="CODE_LANGUAGE" content="Visual Basic .NET 7.1">
		<meta name="vs_defaultClientScript" content="JavaScript">
		<meta name="vs_targetSchema" content="http://schemas.microsoft.com/intellisense/ie5">
		<LINK href="../attributes/CSS/noyce.css" type="text/css" rel="stylesheet">
		<link rel="stylesheet" href="../attributes/CSS/Styles.css" type="text/css">
	</HEAD>
	<body>
		<form id="Form1" method="post" runat="server">
			<table cellspacing="0" cellpadding="0" rules="all" bordercolor="#cc9966" border="1" id="header"
				style="BORDER-RIGHT:#cc9966 1px; BORDER-TOP:#cc9966 1px; BORDER-LEFT:#cc9966 1px; WIDTH:100%; BORDER-BOTTOM:#cc9966 1px; BORDER-COLLAPSE:collapse; BACKGROUND-COLOR:white">
				<tr style="COLOR:#ffffcc;BACKGROUND-COLOR:#990000">
					<td colspan="2">
						<uc1:header id="Header1" runat="server"></uc1:header>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="right">
						<input type="button" value="<= back" OnClick="javascript: history.back();"><asp:Button id="btnNewSearch" runat="server" Text="new search"></asp:Button>
					</td>
				</tr>
			</table>
			<br>
			<table cellspacing="0" cellpadding="4" rules="all" bordercolor="#cc9966" border="1" id="DataGrid1"
				width="100%" style="BORDER-RIGHT:#cc9966 1px; BORDER-TOP:#cc9966 1px; BORDER-LEFT:#cc9966 1px; BORDER-BOTTOM:#cc9966 1px; BORDER-COLLAPSE:collapse; BACKGROUND-COLOR:white">
				<tr class="SubTitle">
					<td>ID</td>
					<td>Type</td>
					<td>Publication year</td>
				</tr>
				<tr>
					<td>&nbsp;
						<asp:Label id="lblID" runat="server"></asp:Label></td>
					<td>&nbsp;
						<asp:Label id="lblType" runat="server"></asp:Label></td>
					<td>&nbsp;
						<asp:Label id="lblPublicationYear" runat="server"></asp:Label></td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr class="SubTitle">
					<td colspan="3">Authors</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;
						<asp:Label id="lblAuthors" runat="server"></asp:Label></td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr class="SubTitle">
					<td colspan="3">Title</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;
						<asp:Label id="lblTitle" runat="server"></asp:Label></td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr class="SubTitle">
					<td colspan="3">Periodical</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;
						<asp:Label id="lblPeriodical" runat="server"></asp:Label></td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr class="SubTitle">
					<td colspan="3">Link</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;
						<asp:Label id="lblLink" runat="server"></asp:Label></td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr class="SubTitle">
					<td colspan="3">Abstract</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;
						<asp:Label id="lblAbstract" runat="server"></asp:Label></td>
				</tr>
			</table>
			<br>
			<table cellspacing="0" cellpadding="0" rules="all" bordercolor="#cc9966" border="1" id="footer"
				style="BORDER-RIGHT:#cc9966 1px; BORDER-TOP:#cc9966 1px; BORDER-LEFT:#cc9966 1px; WIDTH:100%; BORDER-BOTTOM:#cc9966 1px; BORDER-COLLAPSE:collapse; BACKGROUND-COLOR:white">
				<tr>
					<td colspan="2">
						<uc1:footer id="Footer1" runat="server"></uc1:footer>
					</td>
				</tr>
			</table>
		</form>
	</body>
</HTML>
