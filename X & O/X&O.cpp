#include "XOmain.hpp"

class XOapp : public wxApp
{
public:
    virtual bool OnInit();
    XOmain *frame = nullptr;
};

wxIMPLEMENT_APP(XOapp);

bool XOapp::OnInit()
{

    frame = new XOmain();
    frame -> Show();

    return 1;
}

