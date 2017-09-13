<%@ Page Language="vb" AutoEventWireup="false" Codebehind="searchResults.aspx.vb" Inherits="NOYCE.searchResults"%>
<%@ Register TagPrefix="uc1" TagName="footer" Src="footer.ascx" %>
<%@ Register TagPrefix="uc1" TagName="header" Src="header.ascx" %>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<title>searchResults</title>
		<meta content="Microsoft Visual Studio .NET 7.1" name="GENERATOR">
		<meta content="Visual Basic .NET 7.1" name="CODE_LANGUAGE">
		<meta content="JavaScript" name="vs_defaultClientScript">
		<meta content="http://schemas.microsoft.com/intellisense/ie5" name="vs_targetSchema">
		<LINK href="../attributes/CSS/noyce.css" type="text/css" rel="stylesheet">
		<LINK href="../attributes/CSS/Styles.css" type="text/css" rel="stylesheet">
	</HEAD>
	<body>
		<form id="Form1" method="post" runat="server">
			<table cellspacing="0" cellpadding="0" rules="all" bordercolor="#cc9966" border="1" id="header"
				style="BORDER-RIGHT:#cc9966 1px; BORDER-TOP:#cc9966 1px; BORDER-LEFT:#cc9966 1px; WIDTH:100%; BORDER-BOTTOM:#cc9966 1px; BORDER-COLLAPSE:collapse; BACKGROUND-COLOR:white">
				<tr style="COLOR:#ffffcc;BACKGROUND-COLOR:#990000">
					<td colspan="3">
						<uc1:header id="Header1" runat="server"></uc1:header>
					</td>
				</tr>
				<tr>
					<td vAlign="top" align="center" width="1%"><b>&nbsp;Search&nbsp;for:&nbsp;</b></td>
					<td vAlign="top" width="80%"><asp:label id="lblSearchParameters" runat="server"></asp:label></td>
					<td vAlign="top"><asp:Button id="btnBack" runat="server" Text="new search" Width="150px"></asp:Button><br>
						<asp:Button id="btnTxt" runat="server" Text="Download .txt" Width="150px"></asp:Button><br>
						<asp:Button id="btnXls" runat="server" Text="Download .xls" Width="150px"></asp:Button></td>
				</tr>
			</table>
			<br>
			<asp:datagrid id="DataGrid1" runat="server" Width="100%" BorderColor="#CC9966" BorderStyle="None"
				BorderWidth="1px" BackColor="White" CellPadding="4" DataKeyField="Noyce_ID" AutoGenerateColumns="False"
				AllowSorting="True" OnSortCommand="SortMyDataGrid">
				<FooterStyle ForeColor="#330099" BackColor="#FFFFCC"></FooterStyle>
				<SelectedItemStyle Font-Bold="True" ForeColor="#663399" BackColor="#FFCC66"></SelectedItemStyle>
				<ItemStyle ForeColor="#330099" BackColor="White"></ItemStyle>
				<HeaderStyle Font-Size="Smaller" Font-Names="Verdana,Helvetica,sans-serif" Font-Bold="True" ForeColor="#FFFFCC"
					BackColor="#990000"></HeaderStyle>
				<Columns>
					<asp:HyperLinkColumn DataNavigateUrlField="Noyce_ID" DataNavigateUrlFormatString="searchResultsDetail.aspx?id={0}"
						DataTextField="Noyce_ID" SortExpression="NOYCE_ID" HeaderText="ID">
						<HeaderStyle HorizontalAlign="Center"></HeaderStyle>
						<ItemStyle HorizontalAlign="Center" Width="5%"></ItemStyle>
					</asp:HyperLinkColumn>
					<asp:BoundColumn DataField="Title" SortExpression="[Title Primary]" HeaderText="Title">
						<HeaderStyle HorizontalAlign="Center"></HeaderStyle>
						<ItemStyle Width="30%"></ItemStyle>
					</asp:BoundColumn>
					<asp:BoundColumn DataField="Year" SortExpression="[Pub Year]" HeaderText="Year">
						<HeaderStyle HorizontalAlign="Center"></HeaderStyle>
						<ItemStyle HorizontalAlign="Center" Width="5%"></ItemStyle>
					</asp:BoundColumn>
					<asp:BoundColumn DataField="Authors" SortExpression="[Authors, Primary]" HeaderText="Authors">
						<HeaderStyle HorizontalAlign="Center"></HeaderStyle>
						<ItemStyle Width="30%"></ItemStyle>
					</asp:BoundColumn>
					<asp:BoundColumn DataField="Periodical" SortExpression="[Periodical Full]" HeaderText="Periodical">
						<HeaderStyle HorizontalAlign="Center"></HeaderStyle>
						<ItemStyle Width="30%"></ItemStyle>
					</asp:BoundColumn>
				</Columns>
				<PagerStyle HorizontalAlign="Center" ForeColor="#330099" BackColor="#FFFFCC"></PagerStyle>
			</asp:datagrid>
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
