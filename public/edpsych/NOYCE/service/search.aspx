<%@ Register TagPrefix="uc1" TagName="sideNav" Src="sideNav.ascx" %>
<%@ Register TagPrefix="uc1" TagName="footer" Src="footer.ascx" %>
<%@ Page Language="vb" SmartNavigation="true" AutoEventWireup="false" Codebehind="search.aspx.vb" Inherits="NOYCE.search"%>
<%@ Register TagPrefix="uc1" TagName="header" Src="header.ascx" %>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
	<HEAD>
		<title>NOYCE-Evaluation-Literature Database Structured Search</title>
		<meta content="Microsoft Visual Studio .NET 7.1" name="GENERATOR">
		<meta content="Visual Basic .NET 7.1" name="CODE_LANGUAGE">
		<meta content="JavaScript" name="vs_defaultClientScript">
		<meta content="http://schemas.microsoft.com/intellisense/ie5" name="vs_targetSchema">
		<LINK href="../attributes/CSS/noyce.css" type="text/css" rel="stylesheet">
		<LINK href="../attributes/CSS/Styles.css" type="text/css" rel="stylesheet">
		<script language="JavaScript">
		<!--			
			function CheckBoxUpdate(FormName, FieldName, Parent) {
				var objCheckBoxes = document.forms[FormName].elements[FieldName];
				if (!objCheckBoxes){
					return;
				}
				var intCheckBoxes = objCheckBoxes.length;
				//if there is only a single checkbox and not a checkbox list
				if (!intCheckBoxes) {
					objCheckBoxes.checked = false;
				}
				//if there is a checkbox list
				else {
					// set the check value for all checkboxes
					for (i=0; i<intCheckBoxes; i++){
						objCheckBoxes[i].checked = false;
					}
				}	
			}		
											
			function ChildCheckBoxUpdate(FormName, FieldName, Parent) {
				var objCheckBoxes = document.forms[FormName].elements[FieldName];
				var objParentCheckBox = document.forms[FormName].elements[Parent];
				if (!objParentCheckBox){
					return;
				}
				var intCheckBoxes = objCheckBoxes.length;
				if (!intCheckBoxes) {
					objParentCheckBox.checked = false;
				}
				else {
					// set the check value for all checkboxes
					var AnyChecked = false;
					for (i=0; i<intCheckBoxes; i++){
						if (objCheckBoxes[i].checked == true) {
							AnyChecked = true;
						}
					}
					if (AnyChecked == true){
						objParentCheckBox.checked = false;
					}
				}	
			}	
			
			
			function PublicationDateUpdate() {
				if (document.Form1.PublicationStartDate != "" || document.Form1.PublicationEndDate != ""){
					document.Form1.Publication_ShowAll.checked = false
				}
			}
			
			function PublicationAllUpdate() {
				if (document.Form1.Publication_ShowAll.checked == true){
					document.Form1.PublicationStartDate.value = "";
					document.Form1.PublicationEndDate.value = "";
				}
			}		
																									
		//-->
		</script>
	</HEAD>
	<body>
		<form id="Form1" method="post" runat="server">
			<table cellspacing="0" cellpadding="0" rules="all" bordercolor="#cc9966" border="5" id="header"
				style="BORDER-RIGHT:#cc9966 1px; BORDER-TOP:#cc9966 1px; BORDER-LEFT:#cc9966 1px; WIDTH:100%; BORDER-BOTTOM:#cc9966 1px; BORDER-COLLAPSE:collapse; BACKGROUND-COLOR:white">
				<tr style="COLOR:#ffffcc;BACKGROUND-COLOR:#990000">
					<td colSpan="2">
						<uc1:header id="Header1" runat="server"></uc1:header></td>
				</tr>
				<tr>
					<td vAlign="top" align="left" id="nav" width="220">
						<uc1:sideNav id="SideNav1" runat="server"></uc1:sideNav></td>
					<td vAlign="top" align="left" width="100%"><!-- InstanceBeginEditable name="body" -->
						<table cellSpacing="2" cellPadding="2" width="100%" border="0">
							<tr vAlign="top" align="left">
								<td colSpan="4"><SPAN class="category">Please complete and submit this form to perform 
										a structured database search.</STRONG></SPAN></td>
							</tr>
							<tr vAlign="top" align="left">
								<td colSpan="4">&nbsp;</td>
							</tr>
							<!-- Type -->
							<tr vAlign="top" align="left">
								<td width="21%"><SPAN class="category">Type</SPAN></td>
								<td width="15%"><input onclick="CheckBoxUpdate('Form1','Type_Search','Type_ShowAll')" type="checkbox" value="All"
										name="Type_ShowAll">Show&nbsp;All</td>
								<td width="10%">&nbsp;-&nbsp;or&nbsp;-&nbsp;</td>
								<td width="54%"><input onclick="ChildCheckBoxUpdate('Form1','Type_Search','Type_ShowAll')" type="checkbox"
										value="Book" name="Type_Search">Book<br>
									<input onclick="ChildCheckBoxUpdate('Form1','Type_Search','Type_ShowAll')" type="checkbox"
										value="Chapter" name="Type_Search">Chapter<br>
									<input onclick="ChildCheckBoxUpdate('Form1','Type_Search','Type_ShowAll')" type="checkbox"
										value="ConferencePaper" name="Type_Search">Conference&nbsp;Paper<br>
									<input onclick="ChildCheckBoxUpdate('Form1','Type_Search','Type_ShowAll')" type="checkbox"
										value="ConferenceProceedings" name="Type_Search">Conference&nbsp;Proceedings<br>
									<input onclick="ChildCheckBoxUpdate('Form1','Type_Search','Type_ShowAll')" type="checkbox"
										value="JournalArticle" name="Type_Search">Journal&nbsp;Article<br>
									<input onclick="ChildCheckBoxUpdate('Form1','Type_Search','Type_ShowAll')" type="checkbox"
										value="Report" name="Type_Search">Report<br>
									<input onclick="ChildCheckBoxUpdate('Form1','Type_Search','Type_ShowAll')" type="checkbox"
										value="Thesis" name="Type_Search">Thesis/Dissertation</td>
							</tr>
							<tr vAlign="top" align="left">
								<td colSpan="4">
									<hr>
								</td>
							</tr>
							<!-- Author -->
							<tr vAlign="top" align="left">
								<td><SPAN class="category">Author</SPAN></td>
								<td><asp:checkbox id="Author_ShowAll" runat="server" Text="Show&amp;nbsp;All"></asp:checkbox></td>
								<td>&nbsp;-&nbsp;or&nbsp;-&nbsp;</td>
								<td><asp:dropdownlist id="Author" runat="server"></asp:dropdownlist></td>
							</tr>
							<tr vAlign="top" align="left">
								<td colSpan="4">
									<hr>
								</td>
							</tr>
							<!-- Publication Year -->
							<tr vAlign="top" align="left">
								<td><SPAN class="category">Publication&nbsp;Year</SPAN></td>
								<td><input onclick="PublicationAllUpdate()" type="checkbox" value="All" name="Publication_ShowAll">Show 
									All</td>
								<td>&nbsp;-&nbsp;or&nbsp;-&nbsp;</td>
								<td>
									<table cellSpacing="0" cellPadding="0" width="100%" border="0">
										<tr>
											<td align="left">from:&nbsp;</td>
											<td><INPUT onblur="PublicationDateUpdate()" type="text" maxLength="4" name="PublicationStartDate">&nbsp;yyyy</td>
										</tr>
										<tr>
											<td align="left">to:&nbsp;</td>
											<td><INPUT onblur="PublicationDateUpdate()" type="text" maxLength="4" name="PublicationEndDate">&nbsp;yyyy</td>
										</tr>
									</table>
								</td>
							<tr vAlign="top" align="left">
								<td colSpan="4">
									<hr>
								</td>
							</tr>
							<!-- Topic -->
							<tr vAlign="top" align="left">
								<td width="21%"><SPAN class="category">Topic</SPAN></td>
								<td width="15%"><input onclick="CheckBoxUpdate('Form1','Topic_Search','Topic_ShowAll')" type="checkbox"
										value="All" name="Topic_ShowAll">Show All</td>
								<td width="10%">&nbsp;-&nbsp;or&nbsp;-&nbsp;</td>
								<td width="54%"><input onclick="ChildCheckBoxUpdate('Form1','Topic_Search','Topic_ShowAll')" type="checkbox"
										value="RecruitmentDistrict" name="Topic_Search">Recruitment&nbsp;District 
									&nbsp;&nbsp;<asp:linkbutton id="btnRecruitmentDistrict" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litRecruitmentDistrict" runat="server" Visible="False">
										<SPAN class="definition">- looks at recruitment of teachers to teaching, focused on 
											the effects of schools and districts on that process.</SPAN><BR>
										<BR>
									</asp:literal><input onclick="ChildCheckBoxUpdate('Form1','Topic_Search','Topic_ShowAll')" type="checkbox"
										value="RecruitmentProgram" name="Topic_Search">Recruitment&nbsp;Program 
									&nbsp;&nbsp;<asp:linkbutton id="btnRecruitmentProgram" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litRecruitmentProgram" runat="server" Visible="False">
										<SPAN class="definition">- looks at recruitment of teachers to teaching, focused on 
											the effects of teacher education programs on that process.</SPAN><BR>
										<BR>
									</asp:literal><input onclick="ChildCheckBoxUpdate('Form1','Topic_Search','Topic_ShowAll')" type="checkbox"
										value="RetentionDistrict" name="Topic_Search">Retention&nbsp;District 
									&nbsp;&nbsp;<asp:linkbutton id="btnRetentionDistrict" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litRetentionDistrict" runat="server" Visible="False">
										<SPAN class="definition">- looks at the retention of teachers in teaching, focused 
											on the effects of schools and districts on that process.</SPAN><BR>
										<BR>
									</asp:literal><input onclick="ChildCheckBoxUpdate('Form1','Topic_Search','Topic_ShowAll')" type="checkbox"
										value="RetentionProgram" name="Topic_Search">Retention&nbsp;Program 
									&nbsp;&nbsp;<asp:linkbutton id="btnRetentionProgram" runat="server">definition</asp:linkbutton>
									<asp:literal id="litRetentionProgram" runat="server" Visible="False">
										<BR>
										<SPAN class="definition">- looks at the retention of teachers in teaching, focused 
											on the effects of teacher education programs on that process.</SPAN><BR>
										<BR>
									</asp:literal></td>
							<tr vAlign="top" align="left">
								<td colSpan="4">
									<hr>
								</td>
							</tr>
							<!-- Grade Level -->
							<tr vAlign="top" align="left">
								<td width="21%"><SPAN class="category">Grade&nbsp;Level</SPAN></td>
								<td width="15%"><input onclick="CheckBoxUpdate('Form1','GradeLevel_Search','GradeLevel_ShowAll')" type="checkbox"
										value="All" name="GradeLevel_ShowAll">Show All</td>
								<td width="10%">&nbsp;-&nbsp;or&nbsp;-&nbsp;</td>
								<td width="54%"><input onclick="ChildCheckBoxUpdate('Form1','GradeLevel_Search','GradeLevel_ShowAll')"
										type="checkbox" value="ElementarySchool" name="GradeLevel_Search">Elementary&nbsp;School<br>
									<input onclick="ChildCheckBoxUpdate('Form1','GradeLevel_Search','GradeLevel_ShowAll')"
										type="checkbox" value="MiddleSchool" name="GradeLevel_Search">Middle&nbsp;School<br>
									<input onclick="ChildCheckBoxUpdate('Form1','GradeLevel_Search','GradeLevel_ShowAll')"
										type="checkbox" value="HighSchool" name="GradeLevel_Search">High&nbsp;School<br>
									<input onclick="ChildCheckBoxUpdate('Form1','GradeLevel_Search','GradeLevel_ShowAll')"
										type="checkbox" value="HigherEducation" name="GradeLevel_Search">Higher&nbsp;Education
								</td>
							<tr vAlign="top" align="left">
								<td colSpan="4">
									<hr>
								</td>
							</tr>
							<!-- Discipline -->
							<tr vAlign="top" align="left">
								<td width="21%"><SPAN class="category">Discipline</SPAN></td>
								<td width="15%"><input onclick="CheckBoxUpdate('Form1','Discipline_Search','Discipline_ShowAll')" type="checkbox"
										value="All" name="Discipline_ShowAll">Show All</td>
								<td width="10%">&nbsp;-&nbsp;or&nbsp;-&nbsp;</td>
								<td width="54%"><input onclick="ChildCheckBoxUpdate('Form1','Discipline_Search','Discipline_ShowAll')"
										type="checkbox" value="PhysicalScience" name="Discipline_Search">Physical&nbsp;Science<br>
									<input onclick="ChildCheckBoxUpdate('Form1','Discipline_Search','Discipline_ShowAll')"
										type="checkbox" value="LifeScience" name="Discipline_Search">Life&nbsp;Science<br>
									<input onclick="ChildCheckBoxUpdate('Form1','Discipline_Search','Discipline_ShowAll')"
										type="checkbox" value="EarthScience" name="Discipline_Search">Earth&nbsp;Science<br>
									<input onclick="ChildCheckBoxUpdate('Form1','Discipline_Search','Discipline_ShowAll')"
										type="checkbox" value="ScienceGeneral" name="Discipline_Search">All&nbsp;Science<br>
									<input onclick="ChildCheckBoxUpdate('Form1','Discipline_Search','Discipline_ShowAll')"
										type="checkbox" value="Mathematics" name="Discipline_Search">Mathematics
								</td>
							<tr vAlign="top" align="left">
								<td colSpan="4">
									<hr>
								</td>
							</tr>
							<!-- Classification -->
							<tr vAlign="top" align="left">
								<td width="21%"><SPAN class="category">Classification</SPAN></td>
								<td width="15%"><input onclick="CheckBoxUpdate('Form1','Classification_Search','Classification_ShowAll')"
										type="checkbox" value="All" name="Classification_ShowAll">Show All</td>
								<td width="10%">&nbsp;-&nbsp;or&nbsp;-&nbsp;</td>
								<td width="54%"><input onclick="ChildCheckBoxUpdate('Form1','Classification_Search','Classification_ShowAll')"
										type="checkbox" value="Empirical" name="Classification_Search">Empirical 
									&nbsp;&nbsp;<asp:linkbutton id="btnEmpirical" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litEmpirical" runat="server" Visible="False">
										<SPAN class="definition">- is primarily based on data, whether qualitative or 
											quantitative.</SPAN><BR>
										<BR>
									</asp:literal><input onclick="ChildCheckBoxUpdate('Form1','Classification_Search','Classification_ShowAll')"
										type="checkbox" value="Theoretical" name="Classification_Search">Theoretical 
									&nbsp;&nbsp;<asp:linkbutton id="btnTheoretical" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litTheoretical" runat="server" Visible="False">
										<SPAN class="definition">- primarily develops a new theoretical view of a problem 
											or a field.</SPAN><BR>
										<BR>
									</asp:literal><input onclick="ChildCheckBoxUpdate('Form1','Classification_Search','Classification_ShowAll')"
										type="checkbox" value="LiteratureReview" name="Classification_Search">Literature&nbsp;Review 
									&nbsp;&nbsp;<asp:linkbutton id="btnLiteratureReview" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litLiteratureReview" runat="server" Visible="False">
										<SPAN class="definition">- primarily reviews existing literature.</SPAN><BR>
										<BR>
									</asp:literal><input onclick="ChildCheckBoxUpdate('Form1','Classification_Search','Classification_ShowAll')"
										type="checkbox" value="Description" name="Classification_Search">Description 
									&nbsp;&nbsp;<asp:linkbutton id="btnDescription" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litDescription" runat="server" Visible="False">
										<SPAN class="definition">- primarily describes a program intended to increase 
											recruitment and/or retention of teachers.</SPAN><BR>
										<BR>
									</asp:literal><input onclick="ChildCheckBoxUpdate('Form1','Classification_Search','Classification_ShowAll')"
										type="checkbox" value="SupplyAndDemand" name="Classification_Search">Supply&nbsp;and&nbsp;Demand 
									&nbsp;&nbsp;<asp:linkbutton id="btnSupplyAndDemand" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litSupplyAndDemand" runat="server" Visible="False">
										<SPAN class="definition">- includes description of current and projected numbers 
											and need for teachers.</SPAN><BR>
										<BR>
									</asp:literal><input onclick="ChildCheckBoxUpdate('Form1','Classification_Search','Classification_ShowAll')"
										type="checkbox" value="Historical" name="Classification_Search">Historical&nbsp;Review 
									&nbsp;&nbsp;<asp:linkbutton id="btnHistorical" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litHistorical" runat="server" Visible="False">
										<SPAN class="definition">- examines data, policy, or theory and how it changed over 
											a period of years.</SPAN><BR>
										<BR>
									</asp:literal><input onclick="ChildCheckBoxUpdate('Form1','Classification_Search','Classification_ShowAll')"
										type="checkbox" value="PolicyAndAdvice" name="Classification_Search">Policy&nbsp;and&nbsp;Advice 
									&nbsp;&nbsp;<asp:linkbutton id="btnPolicyAndAdvice" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litPolicyAndAdvice" runat="server" Visible="False">
										<SPAN class="definition">- includes a focus on current policy or advice for 
											improving policy.</SPAN><BR>
										<BR>
									</asp:literal></td>
							<tr vAlign="top" align="left">
								<td colSpan="4">
									<hr>
								</td>
							</tr>
							<!-- Attributes -->
							<tr vAlign="top" align="left">
								<td width="21%"><SPAN class="category">Attributes</SPAN></td>
								<td width="15%"><input onclick="CheckBoxUpdate('Form1','Attributes_Search','Attributes_ShowAll')" type="checkbox"
										value="All" name="Attributes_ShowAll">Show All</td>
								<td width="10%">&nbsp;-&nbsp;or&nbsp;-&nbsp;</td>
								<td width="54%"><input onclick="ChildCheckBoxUpdate('Form1','Attributes_Search','Attributes_ShowAll')"
										type="checkbox" value="HighNeed" name="Attributes_Search">High&nbsp;Need&nbsp;Students&nbsp;or&nbsp;Schools 
									&nbsp;&nbsp;<asp:linkbutton id="btnHighNeed" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litHighNeed" runat="server" Visible="False">
										<SPAN class="definition">- discusses students or schools that are identified as 
											high need (e.g., high risk, underserved, low performing, disadvantaged, hard to 
											staff).</SPAN><BR>
										<BR>
									</asp:literal><input onclick="ChildCheckBoxUpdate('Form1','Attributes_Search','Attributes_ShowAll')"
										type="checkbox" value="MinorityTeachers" name="Attributes_Search">Teachers&nbsp;of&nbsp;Color 
									&nbsp;&nbsp;<asp:linkbutton id="btnMinorityTeachers" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litMinorityTeachers" runat="server" Visible="False">
										<SPAN class="definition">- includes attention to teachers of color.</SPAN><BR>
										<BR>
									</asp:literal><input onclick="ChildCheckBoxUpdate('Form1','Attributes_Search','Attributes_ShowAll')"
										type="checkbox" value="LowSES" name="Attributes_Search">Low&nbsp;SES&nbsp;Students&nbsp;or&nbsp;Schools 
									&nbsp;&nbsp;<asp:linkbutton id="btnLowSES" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litLowSES" runat="server" Visible="False">
										<SPAN class="definition">- includes attention to low SES students, or to schools 
											where many students are low SES.</SPAN><BR>
										<BR>
									</asp:literal><input onclick="ChildCheckBoxUpdate('Form1','Attributes_Search','Attributes_ShowAll')"
										type="checkbox" value="MinorityStudents" name="Attributes_Search">Students&nbsp;of&nbsp;Color 
									&nbsp;&nbsp;<asp:linkbutton id="btnMinorityStudents" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litMinorityStudents" runat="server" Visible="False">
										<SPAN class="definition">- includes attention to students of color.</SPAN><BR>
										<BR>
									</asp:literal><input onclick="ChildCheckBoxUpdate('Form1','Attributes_Search','Attributes_ShowAll')"
										type="checkbox" value="Urban" name="Attributes_Search">Urban &nbsp;&nbsp;<asp:linkbutton id="btnUrban" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litUrban" runat="server" Visible="False">
										<SPAN class="definition">- discusses urban schools and schooling.</SPAN><BR>
										<BR>
									</asp:literal><input onclick="ChildCheckBoxUpdate('Form1','Attributes_Search','Attributes_ShowAll')"
										type="checkbox" value="Rural" name="Attributes_Search">Rural &nbsp;&nbsp;<asp:linkbutton id="btnRural" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litRural" runat="server" Visible="False">
										<SPAN class="definition">- discusses rural schools and schooling.</SPAN><BR>
										<BR>
									</asp:literal><input onclick="ChildCheckBoxUpdate('Form1','Attributes_Search','Attributes_ShowAll')"
										type="checkbox" value="Money" name="Attributes_Search">Teacher&nbsp;pay&nbsp;and&nbsp;other&nbsp;financial&nbsp;factors 
									&nbsp;&nbsp;<asp:linkbutton id="btnMoney" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litMoney" runat="server" Visible="False">
										<SPAN class="definition">- discusses a broad range of financial compensations to 
											teachers currently teaching K-12, including salary, bonuses, fellowships, and 
											others, whether or not it is a specific program designed to increase 
											recruitment or retention.</SPAN><BR>
										<BR>
									</asp:literal><input onclick="ChildCheckBoxUpdate('Form1','Attributes_Search','Attributes_ShowAll')"
										type="checkbox" value="AlternativeCertification" name="Attributes_Search">Alternative&nbsp;Certification 
									&nbsp;&nbsp;<asp:linkbutton id="btnAlternativeCertification" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litAlternativeCertification" runat="server" Visible="False">
										<SPAN class="definition">- discusses alternative certification programs for teacher 
											education.</SPAN><BR>
										<BR>
									</asp:literal><input onclick="ChildCheckBoxUpdate('Form1','Attributes_Search','Attributes_ShowAll')"
										type="checkbox" value="MentoringInduction" name="Attributes_Search">Mentoring&nbsp;/&nbsp;Induction 
									&nbsp;&nbsp;<asp:linkbutton id="btnMentoringInduction" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litMentoringInduction" runat="server" Visible="False">
										<SPAN class="definition">- discusses mentoring or induction programs for new 
											teachers.</SPAN><BR>
										<BR>
									</asp:literal><input onclick="ChildCheckBoxUpdate('Form1','Attributes_Search','Attributes_ShowAll')"
										type="checkbox" value="TeacherQuality" name="Attributes_Search">Teacher&nbsp;Quality 
									&nbsp;&nbsp;<asp:linkbutton id="btnTeacherQuality" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litTeacherQuality" runat="server" Visible="False">
										<SPAN class="definition">- examines questions of teacher quality.</SPAN><BR>
										<BR>
									</asp:literal></td>
							<tr vAlign="top" align="left">
								<td colSpan="4">
									<hr>
								</td>
							</tr>
							<!-- NOYCE Funded -->
							<tr vAlign="top" align="left">
								<td width="21%"><SPAN class="category">NOYCE&nbsp;Funded</SPAN></td>
								<td width="15%"><input onclick="CheckBoxUpdate('Form1','NOYCE_Search','NOYCE_ShowAll')" type="checkbox"
										value="All" name="NOYCE_ShowAll">Show All</td>
								<td width="10%">&nbsp;-&nbsp;or&nbsp;-&nbsp;</td>
								<td width="54%"><input onclick="ChildCheckBoxUpdate('Form1','NOYCE_Search','NOYCE_ShowAll')" type="checkbox"
										value="Yes" name="NOYCE_Search">yes<br>
									<input onclick="ChildCheckBoxUpdate('Form1','NOYCE_Search','NOYCE_ShowAll')" type="checkbox"
										value="No" name="NOYCE_Search">no</td>
							</tr>
							<tr vAlign="top" align="left">
								<td colSpan="4">
									<hr>
								</td>
							</tr>
							<!-- Incentive -->
							<tr vAlign="top" align="left">
								<td width="21%"><SPAN class="category">Incentive</SPAN></td>
								<td width="15%"><input onclick="CheckBoxUpdate('Form1','Incentive_Search','Incentive_ShowAll')" type="checkbox"
										value="All" name="Incentive_ShowAll">Show All</td>
								<td width="10%">&nbsp;-&nbsp;or&nbsp;-&nbsp;</td>
								<td width="54%"><input onclick="ChildCheckBoxUpdate('Form1','Incentive_Search','Incentive_ShowAll')" type="checkbox"
										value="Yes" name="Incentive_Search">yes &nbsp;&nbsp;<asp:linkbutton id="btnIncentive" runat="server">definition</asp:linkbutton><br>
									<asp:literal id="litIncentive" runat="server" Visible="False">
										<SPAN class="definition">- discusses financial compensation programs (often 
											scholarship or loan forgiveness) specifically implemented to increase 
											recruitment or retention.</SPAN><BR>
										<BR>
									</asp:literal><input onclick="ChildCheckBoxUpdate('Form1','Incentive_Search','Incentive_ShowAll')" type="checkbox"
										value="No" name="Incentive_Search">no</td>
							</tr>
							<tr vAlign="top" align="left">
								<td colSpan="4">
									<hr>
								</td>
							</tr>
							<!-- Submit Search -->
							<tr vAlign="top" align="left">
								<td><SPAN class="category">Submit&nbsp;Search</SPAN></td>
								<td colSpan="3"><asp:button id="btnSubmit" runat="server" Text="submit"></asp:button>&nbsp;<asp:button id="btnReset" runat="server" Text="clear selections"></asp:button></td>
							</tr>
							<tr vAlign="top" align="left">
								<td colSpan="4">
									&nbsp;
								</td>
							</tr>
						</table>
						<!-- InstanceEndEditable --></td>
				</tr>
				<tr>
					<td colSpan="2">
						<uc1:footer id="Footer1" runat="server"></uc1:footer>
					</td>
				</tr>
			</table>
		</form>
	</body>
</HTML>
