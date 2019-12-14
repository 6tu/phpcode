/* --------------------------------------------------------------------------

web2pici is part of the pici-server for Linux of the artproject picidae 
http://www.picidae.net
Copyright (c) 2008  picidae.net by christoph wachter and mathias jud

web2pici makes screenshots of webpages, analyzes the webpage structure 
and writes image-maps of the links as well as forms that are placed on 
the exact position of the old form.

This program is based on web2pici from Simom MacMullen et al.
It was extende by picidae.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

-------------------------------------------------------------------------- */


#ifndef _WEB2PICI_H_
#define _WEB2PICI_H_

#include <kapplication.h>

class KHTMLPart;
class KCmdLineArgs;

class web2pici : public KApplication
{
    Q_OBJECT
    
    KHTMLPart *m_html;
    bool m_completed;
    bool browser;
    bool loadingCompleted; //indicates if the page is loaded completely
    bool detectionCompleted;
    bool killPopup;
    
    QString autoDetectId;
    QString filename;
    QRect   rect;
    QPixmap *pix;

    int xVisible;
    int yVisible;

    uint timeoutMillis; // maximum milliseconds to wait for page to load

    public:
        web2pici(const KCmdLineArgs* const args);
        ~web2pici();
        bool save() const;

    protected:
        virtual bool eventFilter(QObject *o, QEvent *e);

    private:
        void init(const QString& path, const bool js = true, 
                                       const bool java = true,
                                       const bool plugins = true,
                                       const bool redirect = true);
        QPixmap *grabChildWidgets(QWidget *w) const;
        void doRendering();
        void resizeClipper(const int width, const int height);
        void formLoop(DOM::NodeList nodeList, QTextStream &stream);

    private slots:
        void openURLRequest(const KURL &url, const KParts::URLArgs & );
        void completed();
};

#endif

