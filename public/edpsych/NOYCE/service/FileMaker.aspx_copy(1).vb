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
    Protected connString As String = ("DSN=NOYCE")


    Private Sub Page_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        'Put user code to initialize the page here

        Dim ds As New DataSet
        Dim da As New OdbcDataAdapter
        Dim sql As String
        Dim RecordCount As Integer
        Dim i As Integer
        Dim j As Integer
        Dim k As Integer
        Dim FileType As String
        Dim MyHost As String
        Dim MyPath As String
        Dim MyMenu As String
        Dim MyMenuPath As String
        Dim ParentMenuPath As String
        Dim FilePath As String
        Dim OutputFolder As String
        Dim RandomFileNumber As Integer
        Dim UNC_Path As String
        Dim ColumnHeadings()
        Dim FieldNames()
        Dim query_string As String
        Dim Text As String


        FileType = Request("FileType")

        'Separate the WHERE clause from the query_string
        query_string = Session("SQL")
        query_string = Right(query_string, Len(query_string) + 1 - InStr(query_string, "FROM"))

        sql = "SELECT NOYCE.NOYCE_ID AS ID, Type.Type, NOYCE.[Pub Year] AS PublicationYear, NOYCE.[Authors, Primary] & ';' & NOYCE.[Authors, Secondary] AS Authors, "
        sql = sql & " NOYCE.[Title Primary] AS Title, NOYCE.[Periodical Full] AS Periodical, NOYCE.Abstract "
        sql = sql & query_string

        'Write out the sql statement
        'Response.Write("sql = " & sql & "<br>")
        'Response.End()

        Dim conn As OdbcConnection
        conn = New Odbc.OdbcConnection(connString)
        conn.Open()

        da.SelectCommand = New OdbcCommand(sql, conn)
        da.Fill(ds, "SelectedRecords")

        RecordCount = ds.Tables("SelectedRecords").Rows.Count
        If RecordCount > 0 Then

            REM** Set up a path where to write the file to	
            MyHost = Request.ServerVariables("HTTP_HOST")
            MyPath = Request.ServerVariables("PATH_INFO")
            MyMenu = "http://" & MyHost & MyPath
            MyMenuPath = Left(MyMenu, InStrRev(MyMenu, "/") - 1)
            ParentMenuPath = Left(MyMenuPath, InStrRev(MyMenuPath, "/"))

            MyPath = Request.ServerVariables("PATH_TRANSLATED")
            MyMenuPath = Left(MyPath, InStrRev(MyPath, "\") - 1)
            MyMenuPath = Left(MyMenuPath, InStrRev(MyMenuPath, "\"))
            FilePath = MyMenuPath & "_public"
            OutputFolder = FilePath



            REM** Name for the file in the output folder
            'Make up a random value to add to the folder name to keep files from overwriting each other
            Randomize()
            RandomFileNumber = Int((10000 * Rnd()) + 1)

            If FileType = "csv" Then
                UNC_Path = OutputFolder & "\NOYCE" & RandomFileNumber & ".csv"
            ElseIf FileType = "xls" Then
                UNC_Path = OutputFolder & "\NOYCE" & RandomFileNumber & ".xls"
            Else
                UNC_Path = OutputFolder & "\NOYCE" & RandomFileNumber & ".txt"
            End If



            REM** Create the new file
            Dim fs, a
            fs = CreateObject("Scripting.FileSystemObject")
            a = fs.CreateTextFile(UNC_Path, True)


            ColumnHeadings = Split("ID;Type;PublicationYear;Authors;Title;Periodical;Abstract", ";")
            FieldNames = Split("ID;Type;PublicationYear;Authors;Title;Periodical;Abstract", ";")


            REM** ---------create a csv file-----------------------------------------------------
            If FileType = "csv" Then
                REM** ---------write out the header
                a.Write("""" & ColumnHeadings(0) & """")
                For i = 1 To UBound(ColumnHeadings)
                    a.Write(",""" & ColumnHeadings(i) & """")
                Next 'i
                a.WriteLine()

                REM** ---------write out the data
                For i = 0 To RecordCount - 1
                    a.Write("""" & ds.Tables("SelectedRecords").Rows(i)(FieldNames(0)) & """")  '1st column
                    For j = 1 To UBound(FieldNames) '2nd thru nth column
                        Text = Replace(ds.Tables("SelectedRecords").Rows(i)(FieldNames(j)) & "", ",", "")
                        a.Write(",""" & Text & """")
                    Next 'j
                    a.WriteLine()
                Next 'i



                REM** ---------create a txt file-----------------------------------------------------
            ElseIf FileType = "txt" Then
                Dim TextLine As String
                Dim TextPointer As Integer

                For i = 0 To RecordCount - 1
                    For j = 0 To UBound(ColumnHeadings)
                        If FieldNames(j) = "Abstract" Then
                            a.Write("" & ColumnHeadings(j) & ": ")
                            a.WriteLine()
                            Text = ds.Tables("SelectedRecords").Rows(i)(FieldNames(j)) & ""
                            Do Until Len(Text) <= 0
                                If Len(Text) > 0 Then
                                    If Len(Text) <= 66 Then
                                        a.Write(Chr(9) & Text & "")
                                        Exit Do
                                    Else
                                        'find the nearest space between words and break the line at that point
                                        TextPointer = 67
                                        For k = 0 To 66
                                            If Mid(Text, TextPointer + k, 1) = Chr(32) Then
                                                TextPointer = TextPointer + k
                                                Exit For
                                            ElseIf Mid(Text, TextPointer - k, 1) = Chr(32) Then
                                                TextPointer = TextPointer - k
                                                Exit For
                                            End If
                                        Next
                                        TextLine = Left(Text, TextPointer)
                                        Text = Right(Text, Len(Text) - TextPointer)
                                        a.Write(Chr(9) & TextLine & "")
                                    End If
                                Else
                                    a.Write(Chr(9) & "")
                                    Exit Do
                                End If
                                'new line for each line of the abstract
                                a.WriteLine()
                            Loop
                            'additional line between the abstract and next record
                            a.WriteLine()

                        Else
                            a.Write("" & ColumnHeadings(j) & ": ")
                            a.Write(Chr(9) & ds.Tables("SelectedRecords").Rows(i)(FieldNames(j)) & "")
                            a.WriteLine()
                        End If
                    Next 'j
                    a.WriteLine()
                Next 'i



                REM** ---------create an xls file-----------------------------------------------------
            Else
                REM** ---------write out the header
                a.Write("" & ColumnHeadings(0) & "")
                For i = 1 To UBound(ColumnHeadings)
                    a.Write(Chr(9) & "" & ColumnHeadings(i) & "")
                Next 'i
                a.WriteLine()


                REM** ---------write out the data
                For i = 0 To RecordCount - 1
                    a.Write(ds.Tables("SelectedRecords").Rows(i)(FieldNames(0)) & Chr(32))  '1st column
                    For j = 1 To UBound(FieldNames)     '2nd thru nth column
                        If FieldNames(j) = "Abstract" Then
                            Text = Replace(ds.Tables("SelectedRecords").Rows(i)(FieldNames(j)) & "", """", "'")
                            a.Write(Chr(9) & Text & Chr(32))
                        Else
                            a.Write(Chr(9) & ds.Tables("SelectedRecords").Rows(i)(FieldNames(j)) & Chr(32))     '2nd thru nth column
                        End If
                    Next 'j
                    a.WriteLine()
                Next 'i



            End If

            a.WriteLine()
            a.WriteLine()


            'Open up the file for the user
            If FileType = "csv" Then
                Response.Redirect(ParentMenuPath & "_public/NOYCE" & RandomFileNumber & ".csv")
            ElseIf FileType = "xls" Then
                Response.Redirect(ParentMenuPath & "_public/NOYCE" & RandomFileNumber & ".xls")
            Else
                Response.Redirect(ParentMenuPath & "_public/NOYCE" & RandomFileNumber & ".txt")
            End If


        Else

            'Display no data
            Response.Write("<html>")
            Response.Write("<head>")
            Response.Write("<meta http-equiv='content-type' content='text/html;charset=iso-8859-1'>")
            Response.Write("<meta name='generator' content='Microsoft FrontPage 4.0'>")
            Response.Write("<title>NOYCE</title>")
            Response.Write("</head>")
            Response.Write("<body bgcolor='#ffffff' link='maroon' vlink='#a9a9a9'>")
            Response.Write("<br><br><br><br><br><br><br><div align='center'><h3>No Data</h3></div><br>")
            Response.Write("</body>")
            Response.Write("</html>")

        End If


    End Sub

End Class
