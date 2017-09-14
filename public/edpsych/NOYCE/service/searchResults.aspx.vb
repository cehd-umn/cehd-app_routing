Imports System.Data
Imports System.Data.Odbc
Imports System.Data.SqlClient

Public Class searchResults
    Inherits System.Web.UI.Page

#Region " Web Form Designer Generated Code "

    'This call is required by the Web Form Designer.
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()

    End Sub
    Protected WithEvents DataGrid1 As System.Web.UI.WebControls.DataGrid
    Protected WithEvents lblSearchParameters As System.Web.UI.WebControls.Label
    Protected WithEvents btnBack As System.Web.UI.WebControls.Button
    Protected WithEvents btnTxt As System.Web.UI.WebControls.Button
    Protected WithEvents btnXls As System.Web.UI.WebControls.Button

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


    Private Sub Page_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        'Put user code to initialize the page here

        '** Testing
        'Response.Write("Search = " & Session("search") & "<br>")
        'Response.Write("Type = " & Session("type") & "<br>")
        'Response.Write("Author = " & Session("author") & "<br>")
        'Response.Write("PublicationStartDate = " & Session("PublicationStartDate") & "<br>")
        'Response.Write("PublicationEndDate = " & Session("PublicationEndDate") & "<br>")
        'Response.Write("Topic = " & Session("topic") & "<br>")
        'Response.Write("GradeLevel = " & Session("GradeLevel") & "<br>")
        'Response.Write("Discipline = " & Session("Discipline") & "<br>")
        'Response.Write("Classification = " & Session("Classification") & "<br>")
        'Response.Write("Attributes = " & Session("Attributes") & "<br>")
        'Response.Write("NOYCE = " & Session("NOYCE") & "<br>")
        'Response.Write("Incentive = " & Session("Incentive") & "<br>")

        If Request("search") & " " <> " " Then
            If Trim(LCase(Request("search"))) = "all" Then   'Show all records by eliminating any previous session criteria
                Session("type") = ""
                Session("author") = ""
                Session("PublicationStartDate") = ""
                Session("PublicationEndDate") = ""
                Session("topic") = ""
                Session("GradeLevel") = ""
                Session("Discipline") = ""
                Session("Classification") = ""
                Session("Attributes") = ""
                Session("NOYCE") = ""
                Session("Incentive") = ""
            End If
        End If

        If Not Page.IsPostBack Then
            GetRecords("[Title Primary]")
            Session("OrderBy") = "[Title Primary]"
        End If

    End Sub

    Sub SortMyDataGrid(ByVal sender As Object, ByVal e As DataGridSortCommandEventArgs)
        Dim NewSort As String
        Dim PrevSort As String

        'Make the column header links reversible
        PrevSort = Session("OrderBy")

        If PrevSort = e.SortExpression Then
            NewSort = e.SortExpression + " DESC"
        Else
            NewSort = e.SortExpression
        End If

        Session("OrderBy") = NewSort

        GetRecords(NewSort)

    End Sub

    Function GetAuthor()
        Dim conn As OdbcConnection
        conn = New Odbc.OdbcConnection(connString)
        conn.Open()

        Dim sql As String
        Dim AuthorName As String

        If Session("Author") <> "All" And Session("Author") <> "0" And Session("Author") & " " <> " " Then
            Dim DataReader As OdbcDataReader
            sql = "SELECT AuthorName FROM Authors WHERE Author_ID = " & Session("author")

            Dim cmd As New OdbcCommand(sql, conn)
            DataReader = cmd.ExecuteReader

            If DataReader.HasRows Then
                DataReader.Read()
                AuthorName = DataReader("AuthorName")
            End If
            DataReader.Close()
        End If

        Return AuthorName

    End Function




    Function GetRecords(ByVal sort As String)

        Dim conn As OdbcConnection
        conn = New Odbc.OdbcConnection(connString)
        conn.Open()

        Dim da As New OdbcDataAdapter
        Dim dt As DataTable
        Dim RecordCount As Integer
        Dim sql As String
        Dim AuthorName As String

        Dim i As Integer
        Dim itemCount As Integer
        Dim FlagFirst As Boolean
        Dim NewCriteria As String
        Dim criteria As String
        Dim criteriaArray


        sql = "SELECT NOYCE.Noyce_ID, NOYCE.[Title Primary] AS Title, NOYCE.[Pub Year] AS Year, NOYCE.[Authors, Primary] & ';' & NOYCE.[Authors, Secondary] AS Authors, NOYCE.[Periodical Full] AS Periodical "

        If Session("Author") = "All" Or Session("Author") = "0" Or Session("Author") & " " = " " Then
            sql &= " FROM NOYCE LEFT OUTER JOIN Type ON NOYCE.Type = Type.ID_Type "
        Else
            sql &= " FROM (NOYCE INNER JOIN NoyceAuthors ON NOYCE.Noyce_ID = NoyceAuthors.Noyce_ID) "
            sql &= " LEFT OUTER JOIN Type ON NOYCE.Type = Type.ID_Type "
        End If


        sql &= " WHERE Evaluation <> 1 "

        '**Type
        If Session("type") & " " <> " " Then
            NewCriteria = ""
            criteriaArray = Split(Session("type"), ",")
            For i = 0 To UBound(criteriaArray)
                criteria = criteriaArray(i)

                Select Case criteria
                    Case "All"
                        'Get all records by default
                    Case "Book"
                        NewCriteria &= ",1"
                    Case "Chapter"
                        NewCriteria &= ",2"
                    Case "ConferencePaper"
                        NewCriteria &= ",3"
                    Case "ConferenceProceedings"
                        NewCriteria &= ",4"
                    Case "JournalArticle"
                        NewCriteria &= ",5"
                    Case "Report"
                        NewCriteria &= ",6"
                    Case "Thesis"
                        NewCriteria &= ",7"
                    Case Else
                        'Get all records by default
                End Select
            Next

            If NewCriteria & " " <> " " Then
                NewCriteria = Right(NewCriteria, Len(NewCriteria) - 1) 'Remove leading comma
                sql = sql & " AND NOYCE.type IN (" & NewCriteria & ") "
            End If
        End If



        '**Author
        If Session("Author") & " " <> " " Then
            If Session("Author") = "All" Or Session("Author") = "0" Or Session("Author") & " " = " " Then
                'Get all records by default
            Else
                sql &= " AND (NoyceAuthors.Author_ID = " & Session("Author") & ")"
            End If
        End If


        '**Publication Year
        If Session("PublicationStartDate") & " " <> " " Then
            If Session("PublicationStartDate") = "All" Then
                'Get all records by default
            Else
                sql &= " AND ([Pub Year] >= " & Session("PublicationStartDate")
                sql &= " AND [Pub Year] <= " & Session("PublicationEndDate") & ") "
            End If
        End If


        '**Topic
        If Session("topic") & " " <> " " Then
            NewCriteria = ""
            criteriaArray = Split(Session("topic"), ",")
            For i = 0 To UBound(criteriaArray)
                criteria = criteriaArray(i)

                Select Case criteria
                    Case "All"
                        'Get all records by default
                    Case "RecruitmentDistrict"
                        NewCriteria &= " OR TopicRecruitmentdistrict = Yes "
                    Case "RecruitmentProgram"
                        NewCriteria &= " OR TopicRecruitmentprogram = Yes "
                    Case "RetentionDistrict"
                        NewCriteria &= " OR TopicRetentiondistrict = Yes "
                    Case "RetentionProgram"
                        NewCriteria &= " OR TopicRetentionprogram = Yes "
                    Case Else
                        'Get all records by default
                End Select
            Next

            If NewCriteria & " " <> " " Then
                NewCriteria = Right(NewCriteria, Len(NewCriteria) - 4) 'Remove leading " OR "
                sql = sql & " AND (" & NewCriteria & ") "
            End If
        End If


        '**Grade Level
        If Session("GradeLevel") & " " <> " " Then
            NewCriteria = ""
            criteriaArray = Split(Session("GradeLevel"), ",")
            For i = 0 To UBound(criteriaArray)
                criteria = criteriaArray(i)

                Select Case criteria
                    Case "All"
                        'Get all records by default
                    Case "ElementarySchool"
                        NewCriteria &= " OR GradeLevelES = Yes "
                    Case "MiddleSchool"
                        NewCriteria &= " OR GradeLevelMS = Yes "
                    Case "HighSchool"
                        NewCriteria &= " OR GradeLevelHS = Yes "
                    Case "HigherEducation"
                        NewCriteria &= " OR GradeLevelHigherEd = Yes "
                    Case Else
                        'Get all records by default
                End Select
            Next

            If NewCriteria & " " <> " " Then
                NewCriteria = Right(NewCriteria, Len(NewCriteria) - 4) 'Remove leading " OR "
                sql = sql & " AND (" & NewCriteria & ") "
            End If
        End If


        '**Discipline
        If Session("Discipline") & " " <> " " Then
            NewCriteria = ""
            criteriaArray = Split(Session("Discipline"), ",")
            For i = 0 To UBound(criteriaArray)
                criteria = criteriaArray(i)

                Select Case criteria
                    Case "All"
                        'Get all records by default
                    Case "PhysicalScience"
                        NewCriteria &= " OR DisciplinePhysScience = Yes "
                    Case "LifeScience"
                        NewCriteria &= " OR DisciplineLifeScience = Yes "
                    Case "EarthScience"
                        NewCriteria &= " OR DisciplineEarthScience = Yes "
                    Case "ScienceGeneral"
                        NewCriteria &= " OR DisciplineBroadly = Yes "
                    Case "Mathematics"
                        NewCriteria &= " OR DisciplineMath = Yes "
                    Case Else
                        'Get all records by default
                End Select
            Next

            If NewCriteria & " " <> " " Then
                NewCriteria = Right(NewCriteria, Len(NewCriteria) - 4) 'Remove leading " OR "
                sql = sql & " AND (" & NewCriteria & ") "
            End If
        End If


        '**Classification
        If Session("Classification") & " " <> " " Then
            NewCriteria = ""
            criteriaArray = Split(Session("Classification"), ",")
            For i = 0 To UBound(criteriaArray)
                criteria = criteriaArray(i)

                Select Case criteria
                    Case "All"
                        'Get all records by default
                    Case "Empirical"
                        NewCriteria &= " OR ClassificationEmpirical = Yes "
                    Case "Theoretical"
                        NewCriteria &= " OR ClassificationTheoretical = Yes "
                    Case "LiteratureReview"
                        NewCriteria &= " OR ClassificationLitReview = Yes "
                    Case "Description"
                        NewCriteria &= " OR ClassificationDescription = Yes "
                    Case "SupplyAndDemand"
                        NewCriteria &= " OR [ClassificationSupply&Demand] = Yes "
                    Case "Historical"
                        NewCriteria &= " OR ClassificationHistorical = Yes "
                    Case "PolicyAndAdvice"
                        NewCriteria &= " OR [ClassificationAdvice&Policy] = Yes "
                    Case Else
                        'Get all records by default
                End Select
            Next

            If NewCriteria & " " <> " " Then
                NewCriteria = Right(NewCriteria, Len(NewCriteria) - 4) 'Remove leading " OR "
                sql = sql & " AND (" & NewCriteria & ") "
            End If
        End If


        '**Attributes
        If Session("Attributes") & " " <> " " Then
            NewCriteria = ""
            criteriaArray = Split(Session("Attributes"), ",")
            For i = 0 To UBound(criteriaArray)
                criteria = criteriaArray(i)

                Select Case criteria
                    Case "All"
                        'Get all records by default
                    Case "HighNeed"
                        NewCriteria &= " OR AttributesHighNeed = Yes "
                    Case "MinorityTeachers"
                        NewCriteria &= " OR AttributesMinorityTeachers = Yes "
                    Case "LowSES"
                        NewCriteria &= " OR AttributesLowIncome = Yes "
                    Case "MinorityStudents"
                        NewCriteria &= " OR AttributesStudentsColor = Yes "
                    Case "Urban"
                        NewCriteria &= " OR AttributesUrban = Yes "
                    Case "Rural"
                        NewCriteria &= " OR AttributesRural = Yes "
                    Case "Money"
                        NewCriteria &= " OR AttributesMoney = Yes "
                    Case "AlternativeCertification"
                        NewCriteria &= " OR ClassificationAlternativeCertification = Yes "
                    Case "MentoringInduction"
                        NewCriteria &= " OR ClassificationMentoringInduction = Yes "
                    Case "TeacherQuality"
                        NewCriteria &= " OR ClassificationTeacherQuality = Yes "

                    Case Else
                        'Get all records by default
                End Select
            Next

            If NewCriteria & " " <> " " Then
                NewCriteria = Right(NewCriteria, Len(NewCriteria) - 4) 'Remove leading " OR "
                sql = sql & " AND (" & NewCriteria & ") "
            End If
        End If


        '**NOYCE Funded
        If Session("NOYCE") & " " <> " " Then
            NewCriteria = ""
            criteriaArray = Split(Session("NOYCE"), ",")
            For i = 0 To UBound(criteriaArray)
                criteria = criteriaArray(i)

                Select Case criteria
                    Case "All"
                        'Get all records by default
                    Case "Yes"
                        NewCriteria &= " OR NOYCEFunded = Yes "
                    Case "No"
                        NewCriteria &= " OR NOYCEFunded = No "
                    Case Else
                        'Get all records by default
                End Select
            Next

            If NewCriteria & " " <> " " Then
                NewCriteria = Right(NewCriteria, Len(NewCriteria) - 4) 'Remove leading " OR "
                sql = sql & " AND (" & NewCriteria & ") "
            End If
        End If

        '**Incentive Program
        If Session("Incentive") & " " <> " " Then
            NewCriteria = ""
            criteriaArray = Split(Session("Incentive"), ",")
            For i = 0 To UBound(criteriaArray)
                criteria = criteriaArray(i)

                Select Case criteria
                    Case "All"
                        'Get all records by default
                    Case "Yes"
                        NewCriteria &= " OR IncentiveProgram = Yes "
                    Case "No"
                        NewCriteria &= " OR IncentiveProgram = No "
                    Case Else
                        'Get all records by default
                End Select
            Next

            If NewCriteria & " " <> " " Then
                NewCriteria = Right(NewCriteria, Len(NewCriteria) - 4) 'Remove leading " OR "
                sql = sql & " AND (" & NewCriteria & ") "
            End If
        End If

        sql &= " ORDER BY " & sort


        'Save as a session variable to use in download
        Session("sql") = sql


        'Write out the sql statement
        'Response.Write("sql = " & sql & "<br>")
        'Response.End()

        dt = New DataTable
        da.SelectCommand = New OdbcCommand(sql, conn)
        da.Fill(dt)

        Dim row As DataRow
        For Each row In dt.Rows
            row("Authors") = Replace(row("Authors"), ";", "; ")
        Next

        DataGrid1.DataSource = dt
        DataGrid1.DataBind()


        '**** Display user selections in report header *****************************
        Dim searchCategories As String
        Dim searchParameters As String
        Dim SelectionString As String
        Dim searchFlag As Boolean = False

        searchParameters = ""

        If Session("type") & " " <> " " Then
            searchParameters &= "<tr><td width='5%'><b>Type:</b><br></td>"
            searchParameters &= "<td align='left'>" & Replace(Session("type"), ",", ", ") & "</td></tr>"
            searchFlag = True
        End If

        If Session("Author") <> "0" And Session("Author") & " " <> " " Then
            searchParameters &= "<tr><td width='5%'><b>Author:</b><br></td>"
            If Session("Author") = "All" Then
                searchParameters &= "<td align='left'>All</td></tr>"
            Else
                searchParameters &= "<td align='left'>" & GetAuthor() & "</td></tr>"
            End If
            searchFlag = True
        End If
        If Session("PublicationStartDate") & " " <> " " Then
            searchParameters &= "<tr><td width='5%'><b>Publications:</b><br></td>"
            If Session("PublicationStartDate") = "All" Then
                searchParameters &= "<td align='left'>All</td></tr>"
            Else
                searchParameters &= "<td align='left'>" & Session("PublicationStartDate") & "&nbsp;to&nbsp;" & Session("PublicationEndDate") & "</td></tr>"
            End If
            searchFlag = True
        End If
        If Session("topic") & " " <> " " Then
            searchParameters &= "<tr><td width='5%'><b>Topic:</b><br></td>"
            If Session("topic") = "All" Then
                searchParameters &= "<td align='left'>All</td></tr>"
            Else
                SelectionString = Replace(Session("topic"), ",", ", ")
                SelectionString = Replace(SelectionString, "RecruitmentDistrict", "Recruitment&nbsp;District")
                SelectionString = Replace(SelectionString, "RecruitmentProgram", "Recruitment&nbsp;Program")
                SelectionString = Replace(SelectionString, "RetentionDistrict", "Retention&nbsp;District")
                SelectionString = Replace(SelectionString, "RetentionProgram", "Retention&nbsp;Program")
                searchParameters &= "<td align='left'>" & SelectionString & "</td></tr>"
            End If
            searchFlag = True
        End If
        If Session("GradeLevel") & " " <> " " Then
            searchParameters &= "<tr><td width='5%'><b>Grade&nbsp;level:</b><br></td>"
            If Session("GradeLevel") = "All" Then
                searchParameters &= "<td align='left'>All</td></tr>"
            Else
                SelectionString = Replace(Session("GradeLevel"), ",", ", ")
                SelectionString = Replace(SelectionString, "ElementarySchool", "Elementary&nbsp;School")
                SelectionString = Replace(SelectionString, "MiddleSchool", "Middle&nbsp;School")
                SelectionString = Replace(SelectionString, "HighSchool", "High&nbsp;School")
                SelectionString = Replace(SelectionString, "HigherEducation", "Higher&nbsp;Education")
                searchParameters &= "<td align='left'>" & SelectionString & "</td></tr>"
            End If
            searchFlag = True
        End If
        If Session("Discipline") & " " <> " " Then
            searchParameters &= "<tr><td width='5%'><b>Discipline:</b><br></td>"
            If Session("Discipline") = "All" Then
                searchParameters &= "<td align='left'>All</td></tr>"
            Else
                SelectionString = Replace(Session("Discipline"), ",", ", ")
                SelectionString = Replace(SelectionString, "PhysicalScience", "Physical&nbsp;Science")
                SelectionString = Replace(SelectionString, "LifeScience", "Life&nbsp;Science")
                SelectionString = Replace(SelectionString, "EarthScience", "Earth&nbsp;Science")
                SelectionString = Replace(SelectionString, "ScienceGeneral", "All&nbsp;Science")
                searchParameters &= "<td align='left'>" & SelectionString & "</td></tr>"
            End If
            searchFlag = True
        End If
        If Session("Classification") & " " <> " " Then
            searchParameters &= "<tr><td width='5%'><b>Classification:</b><br></td>"
            If Session("Classification") = "All" Then
                searchParameters &= "<td align='left'>All</td></tr>"
            Else
                SelectionString = Replace(Session("Classification"), ",", ", ")
                SelectionString = Replace(SelectionString, "LiteratureReview", "Literature&nbsp;Review")
                SelectionString = Replace(SelectionString, "SupplyAndDemand", "Supply&nbsp;and&nbsp;Demand")
                SelectionString = Replace(SelectionString, "Historical", "Historical&nbsp;Review")
                SelectionString = Replace(SelectionString, "PolicyAndAdvice", "Policy&nbsp;and&nbsp;Advice")
                searchParameters &= "<td align='left'>" & SelectionString & "</td></tr>"
            End If
            searchFlag = True
        End If
        If Session("Attributes") & " " <> " " Then
            searchParameters &= "<tr><td width='5%'><b>Attributes:</b><br></td>"
            If Session("Attributes") = "All" Then
                searchParameters &= "<td align='left'>All</td></tr>"
            Else
                SelectionString = Replace(Session("Attributes"), ",", ", ")
                SelectionString = Replace(SelectionString, "HighNeed", "High&nbsp;Need&nbsp;Students&nbsp;or&nbsp;Schools")
                SelectionString = Replace(SelectionString, "MinorityTeachers", "Teachers&nbsp;of&nbsp;Color")
                SelectionString = Replace(SelectionString, "LowSES", "Low&nbsp;SES&nbsp;Students&nbsp;or&nbsp;Schools")
                SelectionString = Replace(SelectionString, "MinorityStudents", "Students&nbsp;of&nbsp;Color")
                SelectionString = Replace(SelectionString, "Money", "Teacher&nbsp;pay&nbsp;and&nbsp;other&nbsp;financial&nbsp;factors")
                SelectionString = Replace(SelectionString, "AlternativeCertification", "Alternative&nbsp;Certification")
                SelectionString = Replace(SelectionString, "MentoringInduction", "Mentoring&nbsp;/&nbsp;Induction")
                SelectionString = Replace(SelectionString, "TeacherQuality", "Teacher&nbsp;Quality")
                searchParameters &= "<td align='left'>" & SelectionString & "</td></tr>"
            End If
            searchFlag = True
        End If
        If Session("NOYCE") & " " <> " " Then
            searchParameters &= "<tr><td width='5%'><b>NOYCE&nbsp;funded:</b><br></td>"
            If Session("NOYCE") = "All" Then
                searchParameters &= "<td align='left'>All</td></tr>"
            Else
                SelectionString = Replace(Session("NOYCE"), ",", ", ")
                searchParameters &= "<td align='left'>" & SelectionString & "</td></tr>"
            End If
            searchFlag = True
        End If
        If Session("Incentive") & " " <> " " Then
            searchParameters &= "<tr><td width='5%'><b>Incentive&nbsp;program:</b><br></td>"
            If Session("Incentive") = "All" Then
                searchParameters &= "<td align='left'>All</td></tr>"
            Else
                SelectionString = Replace(Session("Incentive"), ",", ", ")
                searchParameters &= "<td align='left'>" & SelectionString & "</td></tr>"
            End If
            searchFlag = True
        End If

        If searchFlag = False Then
            searchParameters = "<tr><td>All records selected</td></tr>"
        End If

        'Add a record count
        searchParameters &= "<tr><td width='5%'><b>Records&nbsp;returned:</b><br></td>"
        searchParameters &= "<td align='left'>" & dt.Rows.Count & "</td></tr>"

        lblSearchParameters.Text = "<table width='100%' border='0'>"
        lblSearchParameters.Text &= searchParameters
        lblSearchParameters.Text &= "</table>"


        Dim cmd As New OdbcCommand(sql, conn)
        Dim DataReader As OdbcDataReader
        DataReader = cmd.ExecuteReader

        Return DataReader

        DataReader.Close()

    End Function

    Private Sub btnBack_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnBack.Click
        Response.Redirect("search.aspx")
    End Sub

    Private Sub btnTxt_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnTxt.Click
        Response.Redirect("FileMaker.aspx?FileType=txt")
    End Sub

    Private Sub btnXls_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnXls.Click
        Response.Redirect("FileMaker.aspx?FileType=xls")
    End Sub
End Class
