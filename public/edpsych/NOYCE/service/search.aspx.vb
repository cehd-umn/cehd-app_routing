Imports System.Data
Imports System.Data.Odbc
Imports System.Data.SqlClient


Public Class search
    Inherits System.Web.UI.Page

#Region " Web Form Designer Generated Code "

    'This call is required by the Web Form Designer.
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()

    End Sub
    Protected WithEvents Type_ShowAll As System.Web.UI.WebControls.CheckBox
    Protected WithEvents Type_Search As System.Web.UI.WebControls.CheckBoxList
    Protected WithEvents Author_ShowAll As System.Web.UI.WebControls.CheckBox
    Protected WithEvents Author As System.Web.UI.WebControls.DropDownList
    Protected WithEvents PublicationYear_ShowAll As System.Web.UI.WebControls.CheckBox
    Protected WithEvents TextBox1 As System.Web.UI.WebControls.TextBox
    Protected WithEvents Textbox3 As System.Web.UI.WebControls.TextBox
    Protected WithEvents Topic_ShowAll As System.Web.UI.WebControls.CheckBox
    Protected WithEvents Topic_Search As System.Web.UI.WebControls.CheckBoxList
    Protected WithEvents GradeLevel_ShowAll As System.Web.UI.WebControls.CheckBox
    Protected WithEvents GradeLevel_Search As System.Web.UI.WebControls.CheckBoxList
    Protected WithEvents Discipline_ShowAll As System.Web.UI.WebControls.CheckBox
    Protected WithEvents Discipline_Search As System.Web.UI.WebControls.CheckBoxList
    Protected WithEvents Classification_ShowAll As System.Web.UI.WebControls.CheckBox
    Protected WithEvents Classification_Search As System.Web.UI.WebControls.CheckBoxList
    Protected WithEvents Attributes_ShowAll As System.Web.UI.WebControls.CheckBox
    Protected WithEvents Attributes_Search As System.Web.UI.WebControls.CheckBoxList
    Protected WithEvents NOYCE_ShowAll As System.Web.UI.WebControls.CheckBox
    Protected WithEvents NOYCE_Search As System.Web.UI.WebControls.CheckBoxList
    Protected WithEvents Incentive_ShowAll As System.Web.UI.WebControls.CheckBox
    Protected WithEvents Incentive_Search As System.Web.UI.WebControls.CheckBoxList
    Protected WithEvents btnSubmit As System.Web.UI.WebControls.Button
    Protected WithEvents test_ShowAll As System.Web.UI.WebControls.CheckBox
    Protected WithEvents test_search As System.Web.UI.WebControls.CheckBoxList
    Protected WithEvents Text1 As System.Web.UI.HtmlControls.HtmlInputText
    Protected WithEvents btnReset As System.Web.UI.WebControls.Button
    Protected WithEvents btnRecruitmentDistrict As System.Web.UI.WebControls.LinkButton
    Protected WithEvents btnRecruitmentProgram As System.Web.UI.WebControls.LinkButton
    Protected WithEvents btnRetentionDistrict As System.Web.UI.WebControls.LinkButton
    Protected WithEvents btnRetentionProgram As System.Web.UI.WebControls.LinkButton
    Protected WithEvents litRecruitmentDistrict As System.Web.UI.WebControls.Literal
    Protected WithEvents litRecruitmentProgram As System.Web.UI.WebControls.Literal
    Protected WithEvents litRetentionDistrict As System.Web.UI.WebControls.Literal
    Protected WithEvents litRetentionProgram As System.Web.UI.WebControls.Literal
    Protected WithEvents btnEmpirical As System.Web.UI.WebControls.LinkButton
    Protected WithEvents litEmpirical As System.Web.UI.WebControls.Literal
    Protected WithEvents btnTheoretical As System.Web.UI.WebControls.LinkButton
    Protected WithEvents litTheoretical As System.Web.UI.WebControls.Literal
    Protected WithEvents btnLiteratureReview As System.Web.UI.WebControls.LinkButton
    Protected WithEvents litLiteratureReview As System.Web.UI.WebControls.Literal
    Protected WithEvents btnDescription As System.Web.UI.WebControls.LinkButton
    Protected WithEvents litDescription As System.Web.UI.WebControls.Literal
    Protected WithEvents btnSupplyAndDemand As System.Web.UI.WebControls.LinkButton
    Protected WithEvents litSupplyAndDemand As System.Web.UI.WebControls.Literal
    Protected WithEvents btnHistorical As System.Web.UI.WebControls.LinkButton
    Protected WithEvents litHistorical As System.Web.UI.WebControls.Literal
    Protected WithEvents btnPolicyAndAdvice As System.Web.UI.WebControls.LinkButton
    Protected WithEvents litPolicyAndAdvice As System.Web.UI.WebControls.Literal
    Protected WithEvents btnHighNeed As System.Web.UI.WebControls.LinkButton
    Protected WithEvents litHighNeed As System.Web.UI.WebControls.Literal
    Protected WithEvents btnMinorityTeachers As System.Web.UI.WebControls.LinkButton
    Protected WithEvents litMinorityTeachers As System.Web.UI.WebControls.Literal
    Protected WithEvents btnLowSES As System.Web.UI.WebControls.LinkButton
    Protected WithEvents litLowSES As System.Web.UI.WebControls.Literal
    Protected WithEvents btnMinorityStudents As System.Web.UI.WebControls.LinkButton
    Protected WithEvents litMinorityStudents As System.Web.UI.WebControls.Literal
    Protected WithEvents btnUrban As System.Web.UI.WebControls.LinkButton
    Protected WithEvents litUrban As System.Web.UI.WebControls.Literal
    Protected WithEvents btnRural As System.Web.UI.WebControls.LinkButton
    Protected WithEvents litRural As System.Web.UI.WebControls.Literal
    Protected WithEvents btnMoney As System.Web.UI.WebControls.LinkButton
    Protected WithEvents litMoney As System.Web.UI.WebControls.Literal
    Protected WithEvents btnAlternativeCertification As System.Web.UI.WebControls.LinkButton
    Protected WithEvents litAlternativeCertification As System.Web.UI.WebControls.Literal
    Protected WithEvents btnMentoringInduction As System.Web.UI.WebControls.LinkButton
    Protected WithEvents litMentoringInduction As System.Web.UI.WebControls.Literal
    Protected WithEvents btnTeacherQuality As System.Web.UI.WebControls.LinkButton
    Protected WithEvents litTeacherQuality As System.Web.UI.WebControls.Literal
    Protected WithEvents btnIncentive As System.Web.UI.WebControls.LinkButton
    Protected WithEvents litIncentive As System.Web.UI.WebControls.Literal

    'NOTE: The following placeholder declaration is required by the Web Form Designer.
    'Do not delete or move it.
    Private designerPlaceholderDeclaration As System.Object

    Private Sub Page_Init(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Init
        'CODEGEN: This method call is required by the Web Form Designer
        'Do not modify it using the code editor.
        InitializeComponent()
    End Sub

#End Region


    Protected connString As String = ("DSN=NOYCE")
    Protected conn As New Odbc.OdbcConnection(connString)


    Private Sub Page_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        'Put user code to initialize the page here

        If Not Page.IsPostBack Then

            conn.Open()
            Dim da As New OdbcDataAdapter
            Dim ds As New DataSet
            Dim RecordCount As Integer
            Dim sql As String

            'This is an MS Access SQL statement
            sql = "SELECT 0 AS Author_ID, '' AS AuthorName FROM Authors WHERE Author_ID = 1 "
            sql &= " UNION ALL "
            sql &= " SELECT Author_ID, AuthorName FROM Authors "
            sql &= " ORDER BY AuthorName "


            da.SelectCommand = New OdbcCommand(sql, conn)
            da.Fill(ds, "NOYCE")

            Dim row As DataRow
            For Each row In ds.Tables("NOYCE").Rows
                If Len(row("AuthorName")) > 40 Then row("AuthorName") = Left(row("AuthorName"), 40)
            Next

            Author.DataSource = ds.Tables("NOYCE")
            Author.DataValueField = "Author_ID"
            Author.DataTextField = "AuthorName"
            Author.DataBind()

            Author.Attributes("OnChange") = "javascript:return document.Form1.Author_ShowAll.checked = false;"
            Author_ShowAll.Attributes("OnClick") = "javascript:return document.Form1.Author.selectedIndex = 0;"

        End If

    End Sub


    Private Sub btnReset_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnReset.Click
        Response.Redirect("search.aspx")

    End Sub

    Private Sub btnSubmit_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnSubmit.Click
        Dim ErrorMsg As String = ""

        If Request("Type_ShowAll") = "All" Then
            Session("type") = "All"
        Else
            Session("type") = Request("Type_Search")
        End If

        If Request("Author_ShowAll") = "on" Then
            Session("author") = "All"
        Else
            Session("author") = Request("Author")
        End If

        If Request("Publication_ShowAll") = "All" Then
            Session("PublicationStartDate") = "All"
            Session("PublicationEndDate") = "All"
        Else
            If Request("PublicationStartDate") & " " <> " " And Request("PublicationEndDate") & " " <> " " Then
                If IsNumeric(Request("PublicationStartDate")) And IsNumeric(Request("PublicationEndDate")) Then
                    Session("PublicationStartDate") = Request("PublicationStartDate")
                    Session("PublicationEndDate") = Request("PublicationEndDate")
                    If Session("PublicationEndDate") < Session("PublicationStartDate") Then
                        Session("PublicationEndDate") = Request("PublicationStartDate")
                        Session("PublicationStartDate") = Request("PublicationEndDate")
                    End If
                Else
                    ErrorMsg = "Publication Dates must be numeric values only."
                End If
            End If
        End If

        If Request("Topic_ShowAll") = "All" Then
            Session("Topic") = "All"
        Else
            Session("Topic") = Request("Topic_Search")
        End If

        If Request("GradeLevel_ShowAll") = "All" Then
            Session("GradeLevel") = "All"
        Else
            Session("GradeLevel") = Request("GradeLevel_Search")
        End If

        If Request("Discipline_ShowAll") = "All" Then
            Session("Discipline") = "All"
        Else
            Session("Discipline") = Request("Discipline_Search")
        End If

        If Request("Classification_ShowAll") = "All" Then
            Session("Classification") = "All"
        Else
            Session("Classification") = Request("Classification_Search")
        End If

        If Request("Attributes_ShowAll") = "All" Then
            Session("Attributes") = "All"
        Else
            Session("Attributes") = Request("Attributes_Search")
        End If

        If Request("NOYCE_ShowAll") = "All" Then
            Session("NOYCE") = "All"
        Else
            Session("NOYCE") = Request("NOYCE_Search")
        End If

        If Request("Incentive_ShowAll") = "All" Then
            Session("Incentive") = "All"
        Else
            Session("Incentive") = Request("Incentive_Search")
        End If

        If ErrorMsg & " " = " " Then
            Response.Redirect("searchResults.aspx")
        Else
            Response.Write("<table width='100%'><tr class='category'><td>")
            Response.Write("<span class='heading'>" & ErrorMsg & "</span>")
            Response.Write("</td></tr></table>")
        End If

    End Sub

    Private Sub btnRecruitmentDistrict_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnRecruitmentDistrict.Click
        If ViewState("RecruitmentDistrict") = True Then
            litRecruitmentDistrict.Visible = False
            ViewState("RecruitmentDistrict") = False
        Else
            litRecruitmentDistrict.Visible = True
            ViewState("RecruitmentDistrict") = True
        End If

    End Sub

    Private Sub btnRecruitmentProgram_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnRecruitmentProgram.Click
        If ViewState("RecruitmentProgram") = True Then
            litRecruitmentProgram.Visible = False
            ViewState("RecruitmentProgram") = False
        Else
            litRecruitmentProgram.Visible = True
            ViewState("RecruitmentProgram") = True
        End If

    End Sub

    Private Sub btnRetentionDistrict_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnRetentionDistrict.Click
        If ViewState("RetentionDistrict") = True Then
            litRetentionDistrict.Visible = False
            ViewState("RetentionDistrict") = False
        Else
            litRetentionDistrict.Visible = True
            ViewState("RetentionDistrict") = True
        End If

    End Sub

    Private Sub btnRetentionProgram_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnRetentionProgram.Click
        If ViewState("RetentionProgram") = True Then
            litRetentionProgram.Visible = False
            ViewState("RetentionProgram") = False
        Else
            litRetentionProgram.Visible = True
            ViewState("RetentionProgram") = True
        End If

    End Sub

    Private Sub btnEmpirical_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnEmpirical.Click
        If ViewState("Empirical") = True Then
            litEmpirical.Visible = False
            ViewState("Empirical") = False
        Else
            litEmpirical.Visible = True
            ViewState("Empirical") = True
        End If
    End Sub

    Private Sub btnTheoretical_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnTheoretical.Click
        If ViewState("Theoretical") = True Then
            litTheoretical.Visible = False
            ViewState("Theoretical") = False
        Else
            litTheoretical.Visible = True
            ViewState("Theoretical") = True
        End If
    End Sub

    Private Sub btnLiteratureReview_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnLiteratureReview.Click
        If ViewState("LiteratureReview") = True Then
            litLiteratureReview.Visible = False
            ViewState("LiteratureReview") = False
        Else
            litLiteratureReview.Visible = True
            ViewState("LiteratureReview") = True
        End If
    End Sub

    Private Sub btnDescription_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnDescription.Click
        If ViewState("Description") = True Then
            litDescription.Visible = False
            ViewState("Description") = False
        Else
            litDescription.Visible = True
            ViewState("Description") = True
        End If
    End Sub

    Private Sub btnSupplyAndDemand_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnSupplyAndDemand.Click
        If ViewState("SupplyAndDemand") = True Then
            litSupplyAndDemand.Visible = False
            ViewState("SupplyAndDemand") = False
        Else
            litSupplyAndDemand.Visible = True
            ViewState("SupplyAndDemand") = True
        End If
    End Sub

    Private Sub btnHistorical_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnHistorical.Click
        If ViewState("Historical") = True Then
            litHistorical.Visible = False
            ViewState("Historical") = False
        Else
            litHistorical.Visible = True
            ViewState("Historical") = True
        End If
    End Sub

    Private Sub btnPolicyAndAdvice_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnPolicyAndAdvice.Click
        If ViewState("PolicyAndAdvice") = True Then
            litPolicyAndAdvice.Visible = False
            ViewState("PolicyAndAdvice") = False
        Else
            litPolicyAndAdvice.Visible = True
            ViewState("PolicyAndAdvice") = True
        End If
    End Sub

    Private Sub btnHighNeed_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnHighNeed.Click
        If ViewState("HighNeed") = True Then
            litHighNeed.Visible = False
            ViewState("HighNeed") = False
        Else
            litHighNeed.Visible = True
            ViewState("HighNeed") = True
        End If
    End Sub

    Private Sub btnMinorityTeachers_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnMinorityTeachers.Click
        If ViewState("MinorityTeachers") = True Then
            litMinorityTeachers.Visible = False
            ViewState("MinorityTeachers") = False
        Else
            litMinorityTeachers.Visible = True
            ViewState("MinorityTeachers") = True
        End If
    End Sub

    Private Sub btnLowSES_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnLowSES.Click
        If ViewState("LowSES") = True Then
            litLowSES.Visible = False
            ViewState("LowSES") = False
        Else
            litLowSES.Visible = True
            ViewState("LowSES") = True
        End If
    End Sub

    Private Sub btnMinorityStudents_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnMinorityStudents.Click
        If ViewState("MinorityStudents") = True Then
            litMinorityStudents.Visible = False
            ViewState("MinorityStudents") = False
        Else
            litMinorityStudents.Visible = True
            ViewState("MinorityStudents") = True
        End If
    End Sub

    Private Sub btnUrban_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnUrban.Click
        If ViewState("Urban") = True Then
            litUrban.Visible = False
            ViewState("Urban") = False
        Else
            litUrban.Visible = True
            ViewState("Urban") = True
        End If
    End Sub

    Private Sub btnRural_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnRural.Click
        If ViewState("Rural") = True Then
            litRural.Visible = False
            ViewState("Rural") = False
        Else
            litRural.Visible = True
            ViewState("Rural") = True
        End If
    End Sub

    Private Sub btnMoney_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnMoney.Click
        If ViewState("Money") = True Then
            litMoney.Visible = False
            ViewState("Money") = False
        Else
            litMoney.Visible = True
            ViewState("Money") = True
        End If
    End Sub

    Private Sub btnAlternativeCertification_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnAlternativeCertification.Click
        If ViewState("AlternativeCertification") = True Then
            litAlternativeCertification.Visible = False
            ViewState("AlternativeCertification") = False
        Else
            litAlternativeCertification.Visible = True
            ViewState("AlternativeCertification") = True
        End If
    End Sub

    Private Sub btnMentoringInduction_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnMentoringInduction.Click
        If ViewState("MentoringInduction") = True Then
            litMentoringInduction.Visible = False
            ViewState("MentoringInduction") = False
        Else
            litMentoringInduction.Visible = True
            ViewState("MentoringInduction") = True
        End If
    End Sub

    Private Sub btnTeacherQuality_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnTeacherQuality.Click
        If ViewState("TeacherQuality") = True Then
            litTeacherQuality.Visible = False
            ViewState("TeacherQuality") = False
        Else
            litTeacherQuality.Visible = True
            ViewState("TeacherQuality") = True
        End If
    End Sub

    Private Sub btnIncentive_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnIncentive.Click
        If ViewState("Incentive") = True Then
            litIncentive.Visible = False
            ViewState("Incentive") = False
        Else
            litIncentive.Visible = True
            ViewState("Incentive") = True
        End If
    End Sub
End Class
