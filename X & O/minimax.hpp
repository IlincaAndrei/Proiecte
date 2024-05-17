#include <algorithm>
#include <cstdlib>

char AI = 'O' ,ply = 'X';
char gBoard[10]={' ',' ',' ',' ',' ',' ',' ',' ',' ','P'};
int mrk,i,n,dif;

bool mutliber()
{
  for(int j=0;j<9;j++)
    if(gBoard[j]==' ')
    return true;

    return false;
}

int evaluare()
{
    if(gBoard[0]==gBoard[1]&&gBoard[1]==gBoard[2]&&gBoard[2]==AI)
            return +10;
    else if(gBoard[3]==gBoard[4]&&gBoard[4]==gBoard[5]&&gBoard[5]==AI)
            return +10;
    else if(gBoard[6]==gBoard[7]&&gBoard[7]==gBoard[8]&&gBoard[8]==AI)
            return +10;

    else if(gBoard[0]==gBoard[3]&&gBoard[3]==gBoard[6]&&gBoard[6]==AI)
            return +10;
    else if(gBoard[1]==gBoard[4]&&gBoard[4]==gBoard[7]&&gBoard[7]==AI)
            return +10;
    else if(gBoard[2]==gBoard[5]&&gBoard[5]==gBoard[8]&&gBoard[8]==AI)
            return +10;

    else if(gBoard[0]==gBoard[4]&&gBoard[4]==gBoard[8]&&gBoard[8]==AI)
            return +10;
    else if(gBoard[2]==gBoard[4]&&gBoard[4]==gBoard[6]&&gBoard[6]==AI)
            return +10;





    else if(gBoard[0]==gBoard[1]&&gBoard[1]==gBoard[2]&&gBoard[2]==ply)
            return -10;
    else if(gBoard[3]==gBoard[4]&&gBoard[4]==gBoard[5]&&gBoard[5]==ply)
            return -10;
    else if(gBoard[6]==gBoard[7]&&gBoard[7]==gBoard[8]&&gBoard[8]==ply)
            return -10;

    else if(gBoard[0]==gBoard[3]&&gBoard[3]==gBoard[6]&&gBoard[6]==ply)
            return -10;
    else if(gBoard[1]==gBoard[4]&&gBoard[4]==gBoard[7]&&gBoard[7]==ply)
            return -10;
    else if(gBoard[2]==gBoard[5]&&gBoard[5]==gBoard[8]&&gBoard[8]==ply)
            return -10;

    else if(gBoard[0]==gBoard[4]&&gBoard[4]==gBoard[8]&&gBoard[8]==ply)
            return -10;
    else if(gBoard[2]==gBoard[4]&&gBoard[4]==gBoard[6]&&gBoard[6]==ply)
            return -10;

            return 0;
}

int minimax(int lant,bool Maxim,int alfa,int beta)
{
   int scor=evaluare(),best;

   if(scor== 10||scor==-10)
     return scor;

   if(!mutliber())
     return 0;

    if(dif == 2)
      if(lant > 0)
        return 0;

   if(Maxim)
   {
       best=-1000;

       for(int j=0;j<9;j++)
       {
           if(gBoard[j]==' ')
           {
               gBoard[j]=AI;
               best=std::max(best,minimax(lant+1,!Maxim,alfa,beta));
               gBoard[j]=' ';

               alfa=std::max(alfa,best);
               if(beta<=alfa)
                break;
       }
    }
    return best-lant;
   }

     else
   {
       best=1000;

       for(int j=0;j<9;j++)
       {
           if(gBoard[j]==' ')
           {
               gBoard[j]=ply;
               best=std::min(best,minimax(lant+1,!Maxim,alfa,beta));
               gBoard[j]=' ';
               beta=std::min(beta,best);
               if(beta<=alfa)
                break;
       }
    }
    return best+lant;
   }
}

int mutareabuna()
{
    int best=-1000,mutare=9,mutval;
    if(!mutliber())
        return -1;
    if(dif==1)
        {
        while(gBoard[mutare]!=' ')
        mutare = rand()%9;

        return mutare;
        }
        else
    for(int j=0; j<9 ;j++)
    {
        if(gBoard[j]==' ')
        {
            gBoard[j]=AI;
            mutval=minimax(0,false,-1000,1000);
            gBoard[j]=' ';
            if(mutval > best)
            {
                best=mutval;
                mutare=j;
            }
        }
    }
  return mutare;

}



