#include <wx/wx.h>
#include "minimax.hpp"

class XOmain : public wxFrame
{
public:
    XOmain();

private:
    wxButton **btn;
    wxButton *btn1, *btn2 ,*R ,*txt,*txt3 ,*txt4 ,*txt5;
    wxStaticText *txt2 ;
    wxPanel *panel_top ,*panel_bottom;
    wxGridSizer *grid;
    wxMenuBar *menu;
    wxMenu *difMenu;
    wxMenuItem *d1 ,*d2 ,*d3 ,*d4;
    void BtnClk(wxCommandEvent& evt);
    void Btn1(wxCommandEvent& evt);
    void Btn2(wxCommandEvent& evt);
    void Restart(wxCommandEvent& evt);
    void Game();
    void Multiplayer(wxCommandEvent& evt);
    void Diff(wxCommandEvent& evt);
    bool GameOver(int& p1 ,int& p2 ,int& p3);
    char cm ,pm;
    int scorx = 0,scoro = 0;

    wxDECLARE_EVENT_TABLE();
};

wxBEGIN_EVENT_TABLE(XOmain ,wxFrame)
EVT_MENU(1000 ,XOmain::Diff)
EVT_MENU(2000 ,XOmain::Diff)
EVT_MENU(3000 ,XOmain::Diff)
EVT_MENU(4000 ,XOmain::Multiplayer)
wxEND_EVENT_TABLE()

void XOmain::Game()
{
    int p1 = -1 ,p2 = -1 ,p3 = -1;
    if(GameOver(p1 ,p2 ,p3))
    {
        if(p1 > -1 )
        {
            if(cm == 'O')
               {
                txt -> SetLabel("X - won!");
                scorx++;
                wxString lbl;
                lbl << scorx;

                txt4 ->SetLabel(lbl);
                txt4 ->Refresh();
               }
            else {
                txt -> SetLabel("O - won!");
                wxString lbl;
                scoro++;
                lbl << scoro;
                txt5 -> SetLabel(lbl);
                txt5 ->Refresh();
            }
        btn[p1] ->SetBackgroundColour(wxColour(96 ,96 ,96));
        btn[p2] ->SetBackgroundColour(wxColour(96 ,96 ,96));
        btn[p3] ->SetBackgroundColour(wxColour(96 ,96 ,96));
        for(int x = 0; x < 3; x++)
            for(int y = 0; y < 3; y++)
             if(btn[y * 3 + x] -> GetLabel() == ' ')
               btn[y * 3 + x] -> Enable(false);
        }
        else txt -> SetLabel("DRAW!");
       btn1 -> SetBackgroundColour(wxColour(64 ,64 ,64));
       btn2 -> SetBackgroundColour(wxColour(64 ,64 ,64));
       txt4 -> SetBackgroundColour(wxColour(128 ,128 ,128));
       txt5 -> SetBackgroundColour(wxColour(128 ,128 ,128));
    }
    else if(cm == AI && dif < 4)
     {
        mrk =mutareabuna();
        btn[mrk] -> SetLabel(AI);
        gBoard[mrk] = AI;
        cm = ply;
        Game();
     }
     else if(dif == 4)
     {
         if(cm == 'X')
         {
   btn1 -> SetBackgroundColour(wxColour(96 ,96 ,96));
   txt4 -> SetBackgroundColour(wxColour(128 ,128 ,128));
   txt5 -> SetBackgroundColour(wxColour(28 ,28 ,26));
   btn2 -> SetBackgroundColour(wxColour(28 ,28 ,26));
         }
         else
         {
   btn1 -> SetBackgroundColour(wxColour(28 ,28 ,26));
   btn2 -> SetBackgroundColour(wxColour(96 ,96 ,96));
   txt5 -> SetBackgroundColour(wxColour(128 ,128 ,128));
   txt4 -> SetBackgroundColour(wxColour(28 ,28 ,26));
         }
     }
}

bool XOmain::GameOver(int& p1 ,int& p2 ,int& p3)
{
    if(gBoard[0]==gBoard[1]&&gBoard[1]==gBoard[2]&&gBoard[2]!=' ')
            {   p1 = 0;
                p2 = 1;
                p3 = 2;
                return true;
            }
    else if(gBoard[3]==gBoard[4]&&gBoard[4]==gBoard[5]&&gBoard[5]!=' ')
            {   p1 = 3;
                p2 = 4;
                p3 = 5;
                return true;
            }
    else if(gBoard[6]==gBoard[7]&&gBoard[7]==gBoard[8]&&gBoard[8]!=' ')
            {   p1 = 6;
                p2 = 7;
                p3 = 8;
                return true;
            }

    else if(gBoard[0]==gBoard[3]&&gBoard[3]==gBoard[6]&&gBoard[6]!=' ')
           {    p1 = 0;
                p2 = 3;
                p3 = 6;
                return true;
            }
    else if(gBoard[1]==gBoard[4]&&gBoard[4]==gBoard[7]&&gBoard[7]!=' ')
            {   p1 = 1;
                p2 = 4;
                p3 = 7;
                return true;
            }
    else if(gBoard[2]==gBoard[5]&&gBoard[5]==gBoard[8]&&gBoard[8]!=' ')
            {   p1 = 2;
                p2 = 5;
                p3 = 8;
                return true;
            }

    else if(gBoard[0]==gBoard[4]&&gBoard[4]==gBoard[8]&&gBoard[8]!=' ')
            {   p1 = 0;
                p2 = 4;
                p3 = 8;
                return true;
            }
    else if(gBoard[2]==gBoard[4]&&gBoard[4]==gBoard[6]&&gBoard[6]!=' ')
            {   p1 = 2;
                p2 = 4;
                p3 = 6;
                return true;
            }

    else if(!mutliber())
    {
        p1 = -1;
        return true;
    }

            return false;
}

XOmain::XOmain() : wxFrame(nullptr ,wxID_ANY ,"X & O" ,wxPoint(30 ,30) ,wxSize(100 ,100) )
{
    cm = 'X';

    menu = new wxMenuBar();
    difMenu = new wxMenu();

    menu -> Append(difMenu ,_("&Difficulty"));
    SetMenuBar(menu);

    d1 = new wxMenuItem(difMenu ,1000 ,_("&Easy\tCtrl+1"));
    d2 = new wxMenuItem(difMenu ,2000 ,_("&Medium\tCtrl+2"));
    d3 = new wxMenuItem(difMenu ,3000 ,_("&Hard\tCtrl+3"));
    d4 = new wxMenuItem(difMenu ,4000 ,_("&Multiplayer\tCtrl+M"));

    difMenu -> Append(d1);
    difMenu -> Append(d2);
    difMenu -> Append(d3);
    difMenu -> AppendSeparator();
    difMenu -> Append(d4);

    panel_top= new wxPanel(this ,wxID_ANY , wxDefaultPosition ,wxSize(100 ,5));
    panel_top -> SetBackgroundColour(wxColour(28 ,28 ,26));

    wxPanel *panel_bottom= new wxPanel(this ,wxID_ANY , wxDefaultPosition ,wxSize(100 ,5));
    panel_bottom -> SetBackgroundColour(wxColour(0 ,0 ,0));

    wxBoxSizer *sizer = new wxBoxSizer(wxVERTICAL);
    wxBoxSizer *sizer2 = new wxBoxSizer(wxHORIZONTAL);
    wxBoxSizer *sizer3 = new wxBoxSizer(wxVERTICAL);

    wxFont font1(20 ,wxFONTFAMILY_DEFAULT ,wxFONTSTYLE_NORMAL ,wxFONTWEIGHT_BOLD ,false);
    wxFont font(65 ,wxFONTFAMILY_DEFAULT ,wxFONTSTYLE_NORMAL ,wxFONTWEIGHT_BOLD ,false);
    wxFont font2(30 ,wxFONTFAMILY_DEFAULT ,wxFONTSTYLE_NORMAL ,wxFONTWEIGHT_BOLD ,false);

    txt = new wxButton(panel_top,wxID_ANY ,"" ,wxPoint (60 ,60), wxSize(20 ,20) ,wxBORDER_NONE);
    txt -> SetBackgroundColour(wxColour(28 ,28 ,26));
    txt -> SetFont(font1);

    btn1 = new wxButton(panel_top ,1001 ,"X" ,wxDefaultPosition ,wxSize(20,20) ,wxBORDER_NONE);
    btn1 -> SetBackgroundColour(wxColour(96 ,96 ,96));
    btn1 -> SetFont(font2);
    btn1 -> Bind(wxEVT_COMMAND_BUTTON_CLICKED ,&XOmain::Btn1 ,this);

    btn2 = new wxButton(panel_top ,1002 ,"O" ,wxDefaultPosition ,wxSize(30 ,10) ,wxBORDER_NONE);
    btn2 -> SetBackgroundColour(wxColour(28 ,28 ,26));
    btn2 -> SetFont(font2);
    btn2 -> Bind(wxEVT_COMMAND_BUTTON_CLICKED ,&XOmain::Btn2 ,this);

    txt3 = new wxButton(panel_top ,wxID_ANY ,"-" ,wxDefaultPosition ,wxSize(60 ,80) ,wxBORDER_NONE);
    txt3 -> SetBackgroundColour(wxColour(28 ,28 ,26));
    txt3 -> SetFont(font);

    txt4 = new wxButton(panel_top ,wxID_ANY ,"0" ,wxDefaultPosition ,wxSize(10 ,10) ,wxBORDER_NONE);
    txt4 -> SetBackgroundColour(wxColour(128 ,128 ,128));
    txt4 -> SetFont(font2);

    txt5 = new wxButton(panel_top ,wxID_ANY ,"0" ,wxDefaultPosition ,wxSize(10 ,10) ,wxBORDER_NONE);
    txt5 -> SetBackgroundColour(wxColour(28 ,28 ,26));
    txt5 -> SetFont(font2);

    sizer -> Add(panel_top ,1 ,wxEXPAND | wxALL );
    sizer -> Add(panel_bottom ,1 ,wxEXPAND | wxALL );

    sizer2 -> Add(btn1 ,2 ,wxEXPAND | wxTOP | wxDOWN ,60 );
    sizer2 -> Add(txt4 ,1 ,wxEXPAND | wxTOP | wxDOWN ,60 );
    sizer2 -> Add(txt3 ,1 ,wxEXPAND | wxTOP | wxDOWN ,60 );
    sizer2 -> Add(txt5 ,1 ,wxEXPAND | wxTOP | wxDOWN ,60 );
    sizer2 -> Add(btn2 ,2 ,wxEXPAND | wxTOP | wxDOWN ,60 );

    sizer3 -> Add(sizer2 ,4 ,wxEXPAND |wxALL);
    sizer3 -> Add(txt ,1 ,wxEXPAND | wxLEFT | wxRIGHT);

    panel_top -> SetSizerAndFit(sizer3);

    btn = new wxButton * [9];
    grid = new wxGridSizer(3 ,3 ,3 ,3 );

    txt2 = new wxStaticText(panel_top ,wxID_ANY ,"Difficulty : Hard" ,wxPoint(0 ,0) ,wxSize(40 ,40));
    txt2 -> SetForegroundColour(wxColour(128 ,128 ,128));
    txt2 -> SetFont(font1);


    R = new wxButton(panel_top ,1003 ,"" ,wxDefaultPosition ,wxDefaultSize ,wxBORDER_NONE);
    R ->Hide();
    R -> Bind(wxEVT_COMMAND_BUTTON_CLICKED ,&XOmain::Restart ,this);

    wxAcceleratorEntry entries[3];
    entries[0].Set(wxACCEL_NORMAL ,'X' ,1001);
    entries[1].Set(wxACCEL_NORMAL ,'O' ,1002);
    entries[2].Set(wxACCEL_NORMAL ,'R' ,1003);
    wxAcceleratorTable accel(3 ,entries);
    this -> SetAcceleratorTable(accel);

    for(int x = 0; x < 3; x++)
        for(int y = 0; y < 3; y++)
    {
        btn[y * 3 + x] = new wxButton(panel_bottom ,y * 3 + x ,' ',wxDefaultPosition ,wxSize(20 ,20) ,wxBORDER_NONE);
        btn[y * 3 + x] -> SetBackgroundColour(wxColour(28 ,28 ,26));
        btn[y * 3 + x] -> SetForegroundColour(wxColour(64 ,64,64));
        btn[y * 3 + x] -> SetFont(font);

        grid -> Add(btn[y * 3 + x] ,1 ,wxEXPAND | wxALL );
        btn[y * 3 + x] -> Bind(wxEVT_COMMAND_BUTTON_CLICKED ,&XOmain::BtnClk ,this );
    }

    panel_bottom -> SetSizer(grid);
    grid -> Layout();
    this -> SetSizerAndFit(sizer);

}

void XOmain::BtnClk(wxCommandEvent& evt)
{
    int x = evt.GetId() % 3;
    int y = evt.GetId() / 3;

    wxString lbl = btn[y * 3 + x] -> GetLabel();
     if(lbl != 'X' && lbl != 'O' && lbl != "  ")
    {
        btn[y * 3 + x] -> SetLabel(cm);
        gBoard[y * 3 + x] = cm;
        if(cm == 'X')
            cm = 'O';
        else cm = 'X';
        Game();
    }

    evt.Skip();
}

void XOmain::Btn1(wxCommandEvent& evt)
{
  if(dif < 4)
  {
   btn1 -> SetBackgroundColour(wxColour(96 ,96 ,96));
   txt4 -> SetBackgroundColour(wxColour(128 ,128 ,128));
   txt5 -> SetBackgroundColour(wxColour(28 ,28 ,26));
   btn2 -> SetBackgroundColour(wxColour(28 ,28 ,26));

   if(ply == 'O')
   {
    std::swap(scorx ,scoro);
    wxString lbl1 ;
    lbl1 << scoro;
    txt5 -> SetLabel(lbl1);
    txt5 ->Refresh();
    wxString lbl2 ;
    lbl2 << scorx;
    txt4 -> SetLabel(lbl2);
    txt4 ->Refresh();
   }
   cm = 'X';
   AI = 'O';
   ply = 'X';
   Restart(evt);
  }
   evt.Skip();
}

void XOmain::Btn2(wxCommandEvent& evt)
{
 if(dif < 4)
 {
   btn1 -> SetBackgroundColour(wxColour(28 ,28 ,26));
   btn2 -> SetBackgroundColour(wxColour(96 ,96 ,96));
   txt5 -> SetBackgroundColour(wxColour(128 ,128 ,128));
   txt4 -> SetBackgroundColour(wxColour(28 ,28 ,26));
   if(ply =='X')
   {
   std::swap(scorx ,scoro);
    wxString lbl1 ;
    lbl1 << scoro;
    txt5 -> SetLabel(lbl1);
    txt5 ->Refresh();
    wxString lbl2 ;
    lbl2 << scorx;
    txt4 -> SetLabel(lbl2);
    txt4 ->Refresh();
   }
    cm = 'X';
   AI = 'X';
   ply = 'O';
   Restart(evt);
   Game();
 }
   evt.Skip();
}

void XOmain::Restart(wxCommandEvent& evt)
{

    for(int x = 0; x < 3; x++)
        for(int y = 0; y < 3; y++)
    {
        btn[y * 3 + x] -> SetLabel(' ');
        btn[y * 3 + x] -> SetBackgroundColour(wxColour(28 ,28 ,26));
        btn[y * 3 + x] -> Enable(true);
        btn[y * 3 + x] -> Refresh();
    }
    txt -> SetLabel("");
    txt -> Refresh();
    for(i = 0 ;i < 9 ;i++)
        gBoard[i] = ' ';
if(dif < 4)
  {
    cm = 'X';
    if(ply == 'X')
{
   btn1 -> SetBackgroundColour(wxColour(96 ,96 ,96));
   btn2 -> SetBackgroundColour(wxColour(28 ,28 ,26));
   txt5 -> SetBackgroundColour(wxColour(28 ,28 ,26));
}
else
{
   btn1 -> SetBackgroundColour(wxColour(28 ,28 ,26));
   btn2 -> SetBackgroundColour(wxColour(96 ,96 ,96));
   txt4 -> SetBackgroundColour(wxColour(28 ,28 ,26));
}
  }
  else if(dif == 4)
    {
        if(pm == 'X')
        {
   btn1 -> SetBackgroundColour(wxColour(96 ,96 ,96));
   btn2 -> SetBackgroundColour(wxColour(28 ,28 ,26));
   txt5 -> SetBackgroundColour(wxColour(28 ,28 ,26));
   cm = 'X';
   pm = 'O';
        }
         else if(pm == 'O')
        {
   btn1 -> SetBackgroundColour(wxColour(28 ,28 ,26));
   btn2 -> SetBackgroundColour(wxColour(96 ,96 ,96));
   txt4 -> SetBackgroundColour(wxColour(28 ,28 ,26));
   cm = 'O';
   pm = 'X';
        }
    }
        Game();

}

void XOmain::Diff(wxCommandEvent& evt)
{
    if(dif == 4)
{
scorx = 0;
scoro = 0;
 wxString lbl1 ;
    lbl1 << scoro;
    txt5 -> SetLabel(lbl1);
    txt5 ->Refresh();
   wxString lbl2 ;
    lbl2 << scorx;
    txt4 -> SetLabel(lbl2);
    txt4 ->Refresh();
}
dif = evt.GetId() / 1000;

if(ply == 'X')
{
   btn1 -> SetBackgroundColour(wxColour(96 ,96 ,96));
   btn2 -> SetBackgroundColour(wxColour(28 ,28 ,26));
   txt4 -> SetBackgroundColour(wxColour(128 ,128 ,128));
   txt5 -> SetBackgroundColour(wxColour(28 ,28 ,26));
}
else
{
   btn1 -> SetBackgroundColour(wxColour(28 ,28 ,26));
   btn2 -> SetBackgroundColour(wxColour(96 ,96 ,96));
   txt5 -> SetBackgroundColour(wxColour(128 ,128 ,128));
   txt4 -> SetBackgroundColour(wxColour(28 ,28 ,26));
}
 Restart(evt);
 if(dif == 1)
  txt2 -> SetLabel("Difficulty : Easy");
 else if(dif == 2)
    txt2 -> SetLabel("Difficulty : Medium");
 else
    txt2 -> SetLabel("Difficulty : Hard");

 evt.Skip();
}

void XOmain::Multiplayer(wxCommandEvent& evt)
{
    if(dif < 4)
    {pm = 'X';
   txt2 -> SetLabel("Multiplayer");
   txt2 -> Refresh();
   scorx = 0;
   scoro = 0;
    }
   wxString lbl1 ;
    lbl1 << scoro;
    txt5 -> SetLabel(lbl1);
    txt5 ->Refresh();
   wxString lbl2 ;
    lbl2 << scorx;
    txt4 -> SetLabel(lbl2);
    txt4 ->Refresh();

   dif = 4;
   Restart(evt);
   Game();
}
