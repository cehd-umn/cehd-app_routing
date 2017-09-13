Imports System.Data
Imports System.Data.Odbc
Imports System.Data.SqlClient

Public Class searchResultsDetail
    Inherits System.Web.UI.Page

#Region " Web Form Designer Generated Code "

    'This call is required by the Web Form Designer.
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()

    End Sub
    Protected WithEvents lblID As System.Web.UI.WebControls.Label
    Protected WithEvents lblType As System.Web.UI.WebControls.Label
    Protected WithEvents lblPublicationYear As System.Web.UI.WebControls.Label
    Protected WithEvents lblAuthors As System.Web.UI.WebControls.Label
    Protected WithEvents lblTitle As System.Web.UI.WebControls.Label
    Protected WithEvents lblPeriodical As System.Web.UI.WebControls.Label
    Protected WithEvents lblAbstract As System.Web.UI.WebControls.Label
    Protected WithEvents btnNewSearch As System.Web.UI.WebControls.Button
    Protected WithEvents lblLink As System.Web.UI.WebControls.Label

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
        conn.Open()
        Dim da As New OdbcDataAdapter
        Dim dt As New DataTable
        Dim Noyce_ID As Integer

        Noyce_ID = Request("id")

        Dim i As Integer
        Dim itemCount As Integer
        Dim sql As String


        sql = "SELECT NOYCE.Noyce_ID, Type.Type, NOYCE.[Pub Year] AS PublicationYear, [Authors, Primary] & ';' & [Authors, Secondary] AS Authors, "
        sql &= " NOYCE.[Title Primary] AS Title, NOYCE.[Periodical Full] AS Periodical, NOYCE.Links, NOYCE.Abstract "
        sql &= " FROM Type INNER JOIN NOYCE ON Type.ID_Type = NOYCE.Type "
        sql &= " WHERE NOYCE.NOYCE_ID = " & Noyce_ID

        'Write out the sql statement
        'Response.Write("sql = " & sql & "<br>")


        da.SelectCommand = New OdbcCommand(sql, conn)
        da.Fill(dt)

        Dim row As DataRow
        For Each row In dt.Rows
            lblID.Text = row("Noyce_ID") & ""
            lblType.Text = row("Type") & ""
            lblPublicationYear.Text = row("PublicationYear") & ""
            lblAuthors.Text = row("Authors") & ""
            lblTitle.Text = row("Title") & ""
            lblPeriodical.Text = row("Periodical") & ""
            If LCase(Left(row("Links") & "", 4)) = "http" Or LCase(Left(row("Links") & "", 3)) = "www" Then
                lblLink.Text = "<a href='" & row("Links") & "'>" & row("Links") & "</a>"
            Else
                lblLink.Text = row("Links") & ""
            End If
            lblAbstract.Text = row("Abstract") & ""
        Next

    End Sub


    Private Sub btnNewSearch_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles btnNewSearch.Click
        Response.Redirect("search.aspx")
    End Sub
End Class
