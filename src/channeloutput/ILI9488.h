/*
 *   ILI9488 Channel Output driver for Falcon Player (FPP)
 *
 *   Copyright (C) 2013 the Falcon Player Developers
 *      Initial development by:
 *      - David Pitts (dpitts)
 *      - Tony Mace (MyKroFt)
 *      - Mathew Mrosko (Materdaddy)
 *      - Chris Pinkham (CaptainMurdoch)
 *      For additional credits and developers, see credits.php.
 *
 *   The Falcon Player (FPP) is free software; you can redistribute it
 *   and/or modify it under the terms of the GNU General Public License
 *   as published by the Free Software Foundation; either version 2 of
 *   the License, or (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program; if not, see <http://www.gnu.org/licenses/>.
 */

#ifndef _ILI9488_H
#define _ILI9488_H

#include "ChannelOutputBase.h"

class ILI9488Output : public ChannelOutputBase {
  public:
	ILI9488Output(unsigned int startChannel, unsigned int channelCount);
	~ILI9488Output();

	int Init(Json::Value config);
	int Close(void);

	int RawSendData(unsigned char *channelData);

	void DumpConfig(void);

  private:
	int   m_rows;
	int   m_cols;
	int   m_pixels;
	void *m_gpio_map;

	volatile unsigned int *m_gpio;

	void ILI9488_Init(void);
	void ILI9488_SendByte(unsigned int cs, unsigned char byte);
	void ILI9488_Command(unsigned char cmd);
	void ILI9488_Data(unsigned char cmd);
	void ILI9488_Cleanup(void);
};

#endif
