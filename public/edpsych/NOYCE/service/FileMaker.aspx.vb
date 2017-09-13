Imports System.Data
Imports System.Data.SqlClient
Imports System.Data.Odbc


Public Class FileMaker
    Inherits System.Web.UI.Page

#Region " Web Form Designer Generated Code "

    'This call is required by the Web Form Designer.
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()

    End Sub

    'NOTE: The following placeholder declaration is required by the Web Form Designer.
    'Do not delete or move it.
    Private designerPlaceholderDeclaration As System.Object

    Private Sub Page_Init(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Init
        'CODEGEN: This method call is required by the Web Form Designer
        'Do not modify it using the code editor.
        InitializeComponent()
    End Sub

#End Region


    'Create connection to DB
    Protected DotToDot As String = ("DSN=NOYCE")


    Private Sub Page_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        'Put user code to initialize the page here
        Dim stringWrite As New System.IO.StringWriter
        Dim htmlWrite As New System.Web.UI.HtmlTextWriter(stringWrite)
        Dim dataReader As OdbcDataReader
        Dim cmd As OdbcCommand
        Dim dg As New DataGrid
        Dim sql As String
        Dim FileType As String
        Dim query_string As String


        Dim htwin = Response
        htwin.clear()
        htwin.charset = ""



        FileType = Request("FileType")
        If FileType = "txt" Then
            htwin.ContentType = "text/plain"
        ElseIf FileType = "xls" Then
            htwin.ContentType = "application/vnd.ms-excel"
        Else
            htwin.ContentType = "text/enriched"
        End If



        'Separate the WHERE clause from the query_string
        query_string = Session("SQL")
        query_string = Right(query_string, Len(query_string) + 1 - InStr(query_string, "FROM"))

        sql = "SELECT NOYCE.NOYCE_ID AS ID, Type.Type, NOYCE.[Pub Year] AS PublicationYear, NOYCE.[Authors, Primary] & ';' & NOYCE.[Authors, Secondary] AS Authors, "
        sql = sql & " NOYCE.[Title Primary] AS Title, NOYCE.[Periodical Full] AS Periodical, NOYCE.Abstract "
        sql = sql & query_string


        'Write out the sql statement
        'Response.Write("sql = " & sql & "<br>")
        'Response.End()


        Dim conn As New Odbc.OdbcConnection(DotToDot)
        conn.Open()

        cmd = New OdbcCommand(sql, conn)

        dataReader = cmd.ExecuteReader()
        dg.DataSource = dataReader


        'Styles
        dg.GridLines = GridLines.Both

        ' Make the header text bold
        dg.HeaderStyle.Font.Bold = True

        'bind the modified datagrid
        dg.DataBind()


        dataReader.Close()
        cmd.Dispose()
        conn.Close()


        'tell the datagrid to render itself to our htmltextwriter
        dg.RenderControl(htmlWrite)

        'output the html
        Response.Write(stringWrite.ToString)
        Response.End()


    End Sub

End Class
