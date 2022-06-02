/*
 * This file is part of the Falcon Player (FPP) and is Copyright (C)
 * 2013-2022 by the Falcon Player Developers.
 *
 * The Falcon Player (FPP) is free software, and is covered under
 * multiple Open Source licenses.  Please see the included 'LICENSES'
 * file for descriptions of what files are covered by each license.
 *
 * This source file is covered under the LGPL v2.1 as described in the
 * included LICENSE.LGPL file.
 */

#include "fpp-pch.h"

#include "OutputTester.h"
#include "channeloutput/ChannelOutputSetup.h"

OutputTester::OutputTester() {

}
OutputTester::~OutputTester() {

}
int OutputTester::Init(Json::Value config) {
    testType = config["type"].asInt();
    return TestPatternBase::Init(config);
}
int OutputTester::SetupTest(void) {
    return TestPatternBase::SetupTest();
}
void OutputTester::CycleData(void) {
    cycleCount++;
}
int OutputTester::OverlayTestData(char* channelData) {
    if (m_testEnabled) {
        OverlayOutputTestData((unsigned char*)channelData, cycleCount, testType);
    }
    return TestPatternBase::OverlayTestData(channelData);
}
void OutputTester::DumpConfig(void) {
    LogDebug(VB_CHANNELOUT, "OutputTester::DumpConfig\n");
    LogDebug(VB_CHANNELOUT, "    testType     : %d\n", testType);
    TestPatternBase::DumpConfig();
}
